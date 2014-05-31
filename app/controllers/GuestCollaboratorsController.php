<?php

class GuestCollaboratorsController extends BaseController {

    public function index($accountID, $contentID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return GuestCollaborator::where('content_id', $contentID)->get();
    }

    public function destroy($accountID, $contentID, $guestID)
    {
        if (!$this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }

        return GuestCollaborator::destroy($guestID);
    }
}
