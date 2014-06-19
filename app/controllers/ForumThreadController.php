<?php

class ForumThreadController extends BaseController {

    public function index($accountID)
    {
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        return ForumThread::with('user')->with('reply_count')->get();
    }

    public function show($accountID, $threadID) 
    {
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        return ForumThread::with('user')->with('replies')->find($threadID);
    }

    public function store($accountID)
    {
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        $thread = new ForumThread();
        $thread->account_id = $accountID;

        if (!$thread->save()) {
            return $this->responseError($thread->errors()->all(':message'));
        }

        $thread = ForumThread::with('user')->find($thread->id);

        $thread->reply_count = 0;

        if ($body = Input::get('body')) {
            $reply = new ForumThreadReply();
            $reply->fill([
                'forum_thread_id' => $thread->id,
                'user_id'         => $thread->user_id,
                'body'            => $body,
            ]);
            $reply->save();

            $thread->reply_count++;
        }

        return $thread;
    }

    public function update($accountID, $threadID)
    {
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        $thread = ForumThread::find($threadID);

        if ($thread->account_id != $accountID) {
            return $this->responseError('Cannot change threads across accounts.');
        }

        if (!$thread->save()) {
            return $this->responseError($thread->errors()->all(':message'));
        }

        return ForumThread::with('user')->find($thread->id);
    }

    public function destroy($accountID, $threadID) 
    {
        if (($response = $this->validateAccount($accountID)) !== true) {
            return $response;
        }

        $thread = ForumThread::find($threadID);

        if ($thread->account_id != $accountID) {
            return $this->responseError('Cannot change threads across accounts');
        }

        if (!$thread->destroy()) {
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