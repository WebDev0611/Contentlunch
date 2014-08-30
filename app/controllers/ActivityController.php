<?php

class ActivityController extends BaseController {

    public function mine($accountID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        $userID = Confide::User()->id;

        return Activity::where('user_id', $userID)->where('is_read', 0)->with('user.image')->with('content')->get();
    }

    public function all($accountID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        $userID = Confide::User()->id;
        $contentIDs = Content::where('account_id', $accountID)->lists('id');

        if (empty($contentIDs)) return [];
        return Activity::whereIn('content_id', $contentIDs)->with('user.image')->with('content')->limit(10)->get();
    }

    public function markAsRead($accountID)
    {
        $userID = Confide::User()->id;
        DB::table(App::make('Activity')->table)->where('user_id', $userID)->update(['is_read' => 1]);
    }

}