<?php

use Launch\OAuth\Service\ServiceFactory;

class GuestCollaboratorsController extends BaseController {

    public function all($accountID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        $query = GuestCollaborator::with('connection')->where('account_id', $accountID);

        if ($limit = Input::get('limit')) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function index($accountID, $contentType, $contentID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return GuestCollaborator::where('content_id', $contentID)
            ->where('content_type', rtrim($contentType, 's'))
            ->with('connection')->get();
    }

    public function me()
    {
        $guest = Session::get('guest');
        if (!$guest) {
            return $this->responseError('Guest is not logged on.', 401);
        }

        $guest = GuestCollaborator::with('connection')
            ->with('content.account')
            ->find($guest->id)->toArray();

        // all of these guests will actually be the same person, but with access to different things
        $guestsAccess = GuestCollaborator::where('connection_user_id', $guest['connection_user_id'])->get();

        $guest['content'] = [];
        $guest['campaigns'] = [];
        foreach ($guestsAccess as $g) {
            $g = $g->toArray();
            if ($g['content_type'] == 'content') {
                $guest['content'][] = Content::with('comments')
                    ->with('campaign')
                    ->with('content_type')
                    ->with('account_connections')
                    ->with('related')
                    ->with('tags')
                    ->with('user')
                    ->with('collaborators')
                    ->with('guest_collaborators')
                    ->find($g['content_id'])->toArray();
            } else { // type == 'campaign'
                $guest['campaigns'][] = Campaign::with('comments')
                    ->with('user')
                    ->find($g['content_id'])->toArray();
            }
        }

        return $guest;
    }

    public function destroy($accountID, $contentType, $contentID, $guestID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return GuestCollaborator::destroy($guestID);
    }

    public function show($accessCode)
    {
        $guest = GuestCollaborator::where('access_code', $accessCode)->with('connection')->first(['id', 'name', 'connection_user_id', 'access_code', 'connection_id', 'content_id', 'type']);

        if (empty($guest)) return Response::json(['message' => 'Access Denied'], 401);
        $guest = $guest->toArray();

        // withs were breaking stuff! so let's do it the old-fashioned way
        $guest['content'] = Content::select('id', 'title', 'concept', 'user_id')->find($guest['content_id'])->toArray();
        $guest['content']['user'] = User::select('first_name', 'last_name', 'id')->find($guest['content']['user_id'])->toArray();

        return $guest;
    }

    /**
     * Goto the provider's url for authenticating with contentlaunch
     * Based on the connection_id url param
     */
    public function linkAccount($guestID)
    {
        $guest = GuestCollaborator::find($guestID);
        if (!$guest) {
            return $this->responseError("Unable to find guest");
        }

        $connection = Connection::find($guest->connection_id);
        if (!$connection) {
            return $this->responseError("Unable to find connection");
        }

        // Set the connection type in the SESSION
        Session::put('connection_id', $connection->id);
        Session::put('guest_id', $guest->id);
        Session::put('action', $guest->type == 'group' ? 'finish_group' : 'finish_guest');
        $service = new ServiceFactory($connection->provider);
        return Redirect::away($service->getAuthorizationUri());
    }

    static function finishGuest() 
    {
        list($guest, $service, $settings, $connectionUserId, $provider) = self::checkRequest();

        if ($connectionUserId !== $guest->connection_user_id) {
            return self::staticResponseError('Account used in invitation does not match that user. Please log in with the account associated with ' . $guest->name);
        }

        $guest->settings = $settings;
        $guest->accepted = true;
        $guest->save();

        Session::put('guest', $guest);

        return Redirect::to("/collaborate/guest/{$guest->content_type}/{$guest->content_id}");
    }

    static function finishGroup()
    {
        list($group, $service, $settings, $connectionUserId, $provider) = self::checkRequest();

        // currently--and hopefully always--only LinkedIn should get here, but just in case...
        $isMemberOfGroup = self::isMemberOfGroup($group->connection_user_id, $service, $provider);

        if (!$isMemberOfGroup) {
            return self::staticResponseError('You are not a member of the group that is allowed access.');
        }

        // we save a new $guest based on the $group
        $guest = $group->cloneMe();
        $guest->connection_user_id = $connectionUserId;
        $guest->settings = $settings;
        $guest->accepted = true;
        $guest->type = 'individual';
        $guest->name = self::getUsersName($service, $provider);

        $guest->save();

        Session::put('guest', $guest);

        return Redirect::to('/create/content/edit/' . $guest->content_id);
    }

    static function getUsersName($service, $provider)
    {
        switch ($provider) {
            case 'linkedin':
                $result = $service->service->request('/people/~:(first-name,last-name)?format=json');
                $name = json_decode($result, true);
                return $name['firstName'] . ' ' . $name['lastName'];
            
            default:
                return false;
        }
    }

    static function extractUserId($token, $service, $provider)
    {
        switch ($provider) {
            case 'twitter':
                // yay Twitter for being simple
                return $token->getExtraParams()['user_id'];
            case 'linkedin':
                $result = $service->service->request('/people/~:(id)?format=json');
                return json_decode($result, true)['id'];
            
            default:
                return false;
        }
    }

    static function isMemberOfGroup($groupId, $service, $provider)
    {
        switch ($provider) {
            case 'linkedin':
                $result = $service->service->request("/groups/{$groupId}:(id,relation-to-viewer:(membership-state,available-actions))?format=json");
                $result = json_decode($result, true);
                $membershipCode = $result['relationToViewer']['membershipState']['code'];
                return in_array($membershipCode, [
                    // should be one of these 8 options:

                    // these 4 should not be allowed
                    // 'blocked',
                    // 'non-member',
                    // 'awaiting-confirmation',
                    // 'awaiting-parent-group-confirmation',

                    // these 4 should be allowed
                    'member',
                    'moderator',
                    'manager',
                    'owner',
                ]);
            
            default:
                return false;
        }
    }


    static function checkRequest()
    {
        $connectionID = Session::get('connection_id'); 
        Session::forget('connection_id');
        $connection = Connection::find($connectionID);
        if (!$connection) {
          return self::staticResponseError('Unable to find connection');
        }

        $guestID = Session::get('guest_id'); 
        Session::forget('guest_id');
        $guest = GuestCollaborator::find($guestID);
        if (!$guest) {
          return self::staticResponseError('Unable to find guest');
        }

        $service = new ServiceFactory($connection->provider);
        $settings = $service->getCallbackData();

        $connectionUserId = self::extractUserId($settings['token'], $service, $connection->provider);
        if (!$connectionUserId) {
            return self::staticResponseError("Could not extract user ID from provider: {$provider}");
        }

        return [$guest, $service, $settings, $connectionUserId, $connection->provider];
    }
}
