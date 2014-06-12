<?php

class CampaignUploadController extends BaseController {

    public function index($accountID, $campaignID)
    {
        $account = Account::find($accountID);
        if (!$account || !$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return Upload::select('uploads.*', 'cu.campaign_id')->where('cu.campaign_id', $campaignID)->join('campaign_uploads as cu', 'upload_id', '=', 'uploads.id')->get(); 
    }

    public function show($accountID, $campaignID, $uploadID)
    {
        // Check user belongs to this account
        $account = Account::find($accountID);
        if (!$account || !$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return Upload::find($uploadID);
    }

    public function store($accountID, $campaignID) 
    {
        $account = Account::find($accountID);
        if (!$account || !$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        // @todo: Check user has permission to upload
        $file = Input::file('file');
        $upload = new Upload;

        // Associate upload with this account
        $upload->account()->associate($account);

        try {
            $upload->process($file);
        } catch (Exception $e) {
            Log::error($e);
            return $this->responseError($e->getMessage());
        }

        if ($upload->id) {
            $result = DB::table('campaign_uploads')->insert([
                'campaign_id' => $campaignID,
                'upload_id' => $upload->id,
            ]);

            if (!$result) {
                return $this->responseError("Could not associate file with campaign");
            }

            return $this->show($accountID, $campaignID, $upload->id);
        }

        return $this->responseError("Unable to upload file");
    }

    public function destroy($accountID, $campaignID, $uploadID)
    {
        // Check user belongs to this account
        $account = Account::find($accountID);
        if (!$account || !$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        // @todo: Check user has permission to delete upload
        $upload = Upload::find($uploadID);
        // Delete relations
        DB::table('campaign_uploads')->where('upload_id', $upload->id)->delete();

        // @todo: Does this delete the actual file from disk?
        if ($upload->delete()) {
            return ['success' => 'OK'];
        }

        return $this->responseError("Unable to delete file");
    }

}
