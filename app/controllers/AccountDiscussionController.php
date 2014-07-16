<?php

class AccountDiscussionController extends BaseController {

    public function index($accountID)
    {
        if (($response = $this->validate($accountID)) !== true) {
            return $response;
        }

        return AccountDiscussion::with('user')->with('account')->where('account_id', $accountID)->get();
    }

    // public function show($accountID, $replyID) {}

    public function store($accountID)
    {
        if (($response = $this->validate($accountID)) !== true) {
            return $response;
        }

        $reply = new AccountDiscussion();

        if (!$reply->save()) {
            return $this->responseError($reply->errors()->all(':message'));
        }

        return AccountDiscussion::with('user')->with('account')->find($reply->id);
    }

    // public function update($accountID, $replyID) {}

    // public function destroy($accountID, $replyID) {}

    private function validate($accountID)
    {
        $user = Confide::user();
        if (!$user) {
            // they they aren't even logged in!
            return $this->responseError('Not logged in', 401);
        }

        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError('Invalid account');
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        return true;
    }

}