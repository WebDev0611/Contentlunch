<?php

/**
 * /api/account/{accountID}/{conceptType}/{conceptID}/traackr-tags
 */
class TraackrTagController extends BaseController 
{

    public function index($accountID, $conceptType, $conceptID)
    {        if (($response = $this->validate($accountID, $conceptType, $conceptID)) !== true) {
            return $response;
        }

        return TraackrTag::where('account_id', $accountID)
                         ->where("{$conceptType}_id", $conceptID)->get();
    }

    public function store($accountID, $conceptType, $conceptID)
    {
        if (($response = $this->validate($accountID, $conceptType, $conceptID)) !== true) {
            return $response;
        }

        if (Input::get('user')) {
            return $this->saveTagWithUser($accountID, $conceptType, $conceptID, Input::get('user'));
        } else if (!($users = Input::get('users')) || empty($users)) {
            return $this->responseError('User or users is a required field to save a traackr tag');
        }

        // handle saving multiple users
        $return = [];
        foreach($users as $user) {
            $tag = $this->saveTagWithUser($accountID, $conceptType, $conceptID, $user);
            if (!empty($tag['error'])) {
                return $tag['error'];
            }
            $return[] = $tag->toArray();
        }

        return ['tags' => $return];
    }

    private function saveTagWithUser($accountID, $conceptType, $conceptID, $user)
    {
        $tag = new TraackrTag();

        $tag->fill([
            'account_id'        => $accountID,
            "{$conceptType}_id" => $conceptID,
            'user'              => $user,
            'traackr_id'        => $user['uid'],
        ]);

        if (!$tag->save()) {
            return ['error' => $this->responseError($tag->errors()->all(':message'))];
        }
        return $tag;
    }

    public function destroy($accountID, $conceptType, $conceptID, $tagID)
    {
        if (($response = $this->validate($accountID, $conceptType, $conceptID)) !== true) {
            return $response;
        }

    }

    private function validate($accountID, $conceptType, $conceptID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        if ($conceptType !== 'campaign' && $conceptType !== 'content') {
            return $this->responseError("{$conceptType} is an invalid concept type", 400);
        }

        return true;
    }

}