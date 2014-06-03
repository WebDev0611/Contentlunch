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
        }])->first(['id', 'name', 'connection_user_id', 'access_code', 'connection_id', 'content_id']);

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
        Session::put('action', 'finish_guest');
        $service = new ServiceFactory($connection->provider);
        return Redirect::away($service->getAuthorizationUri());
    }

    static function finishGuest() 
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

        // TODO see if we need to do something different for LinkedIn
        if ($settings['token']
                ->getExtraParams()['user_id'] !== $guest->connection_user_id) {
            return self::staticResponseError('Account used in invitation does not match that user. Please lot in with the account associated with ' . $guest->name);
        }

        $guest->settings = $settings;
        $guest->accepted = true;
        $guest->save();

        return Redirect::to('/content/' . $guest->content_id);
    }
}
