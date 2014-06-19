<?php

class ForumThreadReplyController extends BaseController {

    public function index($accountID, $threadID)
    {
        if (($response = $this->validate($accountID, $threadID)) !== true) {
            return $response;
        }

        return ForumThreadReply::with('user')->with('account')->where('forum_thread_id', $threadID)->get();
    }

    // public function show($accountID, $threadID, $replyID) {}

    public function store($accountID, $threadID)
    {
        if (($response = $this->validate($accountID, $threadID)) !== true) {
            return $response;
        }

        $reply = new ForumThreadReply();
        $reply->account_id = $accountID;

        if (!$reply->save()) {
            return $this->responseError($reply->errors()->all(':message'));
        }

        return ForumThreadReply::with('user')->with('account')->find($reply->id);
    }

    public function update($accountID, $threadID, $replyID)
    {
        if (($response = $this->validate($accountID, $threadID)) !== true) {
            return $response;
        }

        $reply = ForumThreadReply::find($replyID);

        if ($reply->account_id != $accountID) {
            return $this->responseError('Cannot update replies across accounts.');
        }
        if ($reply->forum_thread_id != $threadID) {
            return $this->responseError('Cannot update replies across threads');
        }

        if (!$reply->save()) {
            return $this->responseError($reply->errors()->all(':message'));
        }

        return ForumThreadReply::with('user')->with('account')->find($reply->id);
    }

    public function destroy($accountID, $threadID, $replyID) 
    {
        if (($response = $this->validate($accountID, $threadID)) !== true) {
            return $response;
        }

        $reply = ForumThreadReply::find($replyID);

        if ($reply->account_id != $accountID) {
            return $this->responseError('Cannot delete replies across accounts');
        }
        if ($reply->forum_thread_id != $threadID) {
            return $this->responseError('Cannot delete replies across threads');
        }

        if (!$reply->delete()) {
            return $this->responseError("Couldn't delete thread");
        }

        return array('success' => 'OK');
    }

    private function validate($accountID, $threadID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        $thread = ForumThread::find($threadID);
        if (!$thread) {
            return $this->responseError("Thread not found", 404);
        }

        return true;
    }

}