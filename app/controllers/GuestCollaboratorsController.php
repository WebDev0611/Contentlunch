<?php

class GuestCollaboratorsController extends BaseController {

    public function index($accountID, $contentID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return GuestCollaborator::where('content_id', $contentID)->get();
    }
}
