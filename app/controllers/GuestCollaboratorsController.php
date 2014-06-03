<?php

use Launch\OAuth\Service\ServiceFactory;

class GuestCollaboratorsController extends BaseController {

    public function index($accountID, $contentID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return GuestCollaborator::where('content_id', $contentID)->with('connection')->get();
    }

    public function destroy($accountID, $contentID, $guestID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return GuestCollaborator::destroy($guestID);
    }

    public function show($accessCode)
    {
        $guest = GuestCollaborator::where('access_code', $accessCode)->with('connection')->with(['content' => function ($query) {
            $query->select('id', 'title', 'concept', 'user_id')->with(['user' => function ($query) {
                $query->select('first_name', 'last_name', 'id');
            }]);
        }])->first(['id', 'name', 'connection_user_id', 'access_code', 'connection_id', 'content_id', 'type']);

        if (!$guest) return Response::json(['message' => 'Access Denied'], 401);

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
            return self::staticResponseError('Account used in invitation does not match that user. Please lot in with the account associated with ' . $guest->name);
        }

        $guest->settings = $settings;
        $guest->accepted = true;
        $guest->save();

        return Redirect::to('/content/' . $guest->content_id);
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

        return Redirect::to('/content/' . $guest->content_id);
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
                $result = $service->service->request("/groups/{$groupId}:(id)?format=json");
                return !!@json_decode($result, true)['id'];
            
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
