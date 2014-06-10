<?php

class CampaignTaskController extends BaseController {

    public function index($accountID, $campaignID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        return CampaignTask::with('campaign')
            ->with('user')
            ->where('campaign_id', $campaignID)
            ->get();
    }

    public function show($accountID, $campaignID, $taskID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        return CampaignTask::with('campaign')
            ->with('user')
            ->find($taskID);
    }

    public function store($accountID, $campaignID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        $task = new CampaignTask;
        $task->campaign_id = $campaignID;
        if ($task->save()) {
            return $this->show($accountID, $campaignID, $task->id);
        }

        return $this->responseError($task->errors()->all(':message'));
    }

    public function update($accountID, $campaignID, $taskID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        $task = CampaignTask::find($taskID);
        if ($task->save()) {
            return $this->show($accountID, $campaignID, $task->id);
        }

        return $this->responseError($task->errors()->all(':message'));
    }

    public function destroy($accountID, $campaignID, $taskID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        $task = CampaignTask::find($taskID);
        if ($task->delete()) {
            return array('success' => 'OK');
        }

        return $this->responseError("Couldn't delete campaign task");
    }

}
