<?php

class AnnouncementsController extends \BaseController {

    /**
     * Display a listing of announcements
     *
     * @return Response
     */
    public function index()
    {
        $user = Confide::user();
        if (!$user) {
            // they they aren't even logged in!
            return $this->responseError('Not logged in', 401);
        }

        $user = $user->toArray();

        if (count($user['hidden_announcements']) > 0) {
            return Announcement::whereNotIn('id', $user['hidden_announcements'])->get();
        } else {
            return Announcement::all();
        }
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (!$this->hasRole('global_admin')) {
            return $this->responseError('You do not have permission to create announcements', 401);   
        }

        $announcement = new Announcement();
        if (!$announcement->save()) {
            return $this->responseError($announcement->errors()->all(':message'));
        }

        return $announcement;
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        if (!$this->hasRole('global_admin')) {
            return $this->responseError('You do not have permission to update announcements', 401);   
        }

        $announcement = Announcement::find($id);
        if (!$announcement->save()) {
            return $this->responseError($announcement->errors()->all(':message'));
        }

        return $announcement;
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if (!$this->hasRole('global_admin')) {
            return $this->responseError('You do not have permission to delete announcements', 401);   
        }

        Announcement::destroy($id);

        return ['success' => 'OK'];
    }

}
