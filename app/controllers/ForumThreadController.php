<?php

class ForumThreadController extends BaseController {

    public function index($accountID)
    {
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        return ForumThread::with('reply_count')->with('user')->with('account')->get();
    }

    public function show($accountID, $threadID) 
    {
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        $thread = ForumThread::with('user')->with('replies')->with('account')->find($threadID);

        if (!$thread) {
            return $this->responseError('Thread not found', 404);
        }

        // increment view count every time we request this resource
        $thread->views++;
        $thread->save();

        return $thread;
    }

    public function store($accountID)
    {
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        if(!$this->hasAbility([], ['consult_execute_forum_create'])) {
          return $this->responseError('You do not have permission to create campaigns', 401);
        }

        $thread = new ForumThread();
        $thread->account_id = $accountID;

        if (!$thread->save()) {
            return $this->responseError($thread->errors()->all(':message'));
        }

        $thread = ForumThread::with('user')->with('account')->find($thread->id);

        if ($body = Input::get('body')) {
            $reply = new ForumThreadReply();

            $reply->forum_thread_id = $thread->id;
            $reply->user_id         = $thread->user_id;
            $reply->body            = $body;
            $reply->account_id      = $thread->account_id;

            if (!$reply->save()) {
                return $this->responseError($reply->errors()->all(':message'));
            }

            $thread->reply_count = 1;
        } else {
            $thread->reply_count = 0;
        }

        return $thread;
    }

    public function update($accountID, $threadID)
    {
        // disable update until we here otherwise
        return $this->responseError('Cannot edit threads', 401);

        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        $thread = ForumThread::find($threadID);

        if (!$thread->save()) {
            return $this->responseError($thread->errors()->all(':message'));
        }

        return ForumThread::with('user')->find($thread->id);
    }

    public function destroy($accountID, $threadID) 
    {
        if (!$this->hasRole('global_admin')) {
            return $this->responseError('You do not have permission to delete threads', 401);   
        }

        // I guess not technically necessary if user is global admin?
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        $thread = ForumThread::find($threadID);

        if (!$thread->delete()) {
            return $this->responseError("Couldn't delete thread");
        }

        return array('success' => 'OK');
    }

    private function validateAccount($accountID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        return true;
    }

}