<?php

class AccountContentGuestCollaboratorsController extends BaseController {

    public function index($accountID, $contentID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return [];
    }
}
