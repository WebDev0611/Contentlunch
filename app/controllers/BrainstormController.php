<?php

class BrainstormController extends BaseController {

    protected $concept;
    protected $brainstorm;

    public function all($accountID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        $query = Brainstorm::where('account_id', $accountID);

        if ($userID = Input::get('user')) {
            $query->where('user_id', $userID);
        }

        return $query->get();
    }

    /**
     * Display a listing of the resource.
     * GET /api/account/:id/:(content|campaign)/:id/brainstorm
     *
     * @return Response
     */
    public function index($accountID, $conceptType, $conceptID)
    {
        if (($response = $this->validate($accountID, $conceptType, $conceptID)) !== true) {
            return $response;
        }

        return Brainstorm::where("{$conceptType}_id", $conceptID)->get();
    }

    /**
     * Display the specified resource.
     * GET /api/account/:id/:(content|campaign)/:id/brainstorm/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($accountID, $conceptType, $conceptID, $id)
    {
        if (($response = $this->validate($accountID, $conceptType, $conceptID, $id)) !== true) {
            return $response;
        }

        return $this->brainstorm;
    }

    /**
     * Store a newly created resource in storage.
     * POST /api/account/:id/:(content|campaign)/:id/brainstorm
     *
     * @return Response
     */
    public function store($accountID, $conceptType, $conceptID)
    {
        if (($response = $this->validate($accountID, $conceptType, $conceptID)) !== true) {
            return $response;
        }

        $this->brainstorm = new Brainstorm();
        if (!$this->brainstorm->save()) {
            return $this->responseError($this->brainstorm->errors()->all(':message'));
        }

        return $this->show($accountID, $conceptType, $conceptID, $this->brainstorm->id);
    }

    /**
     * Update the specified resource in storage.
     * PUT /api/account/:id/:(content|campaign)/:id/brainstorm/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($accountID, $conceptType, $conceptID, $id)
    {
        if (($response = $this->validate($accountID, $conceptType, $conceptID, $id)) !== true) {
            return $response;
        }

        if (!$this->brainstorm->save()) {
            return $this->responseError($this->brainstorm->errors()->all(':message'));
        }

        return $this->show($accountID, $conceptType, $conceptID, $this->brainstorm->id);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /api/account/:id/:(content|campaign)/:id/brainstorm/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($accountID, $conceptType, $conceptID, $id)
    {
        if (($response = $this->validate($accountID, $conceptType, $conceptID, $id)) !== true) {
            return $response;
        }

        if (!$this->brainstorm->delete()) {
            return $this->responseError("Couldn't delete campaign");
        }

        return array('success' => 'OK');
    }

    protected function validate($accountID, $conceptType, $conceptID, $id = false)
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

        if ($conceptType === 'campaign') {
            $this->concept = Campaign::find($conceptID);
        } else { // then type === 'content'
            $this->concept = Content::find($conceptID);
        }

        if (!$this->concept) {
            return $this->responseError("{$conceptType} not found", 404);
        }

        if ($this->concept->status != 0) {
            return $this->responseError("Can only manipulate brainstorms when dealing with a {$conceptType} in the concept stage.", 400);
        }

        if ($id) {
            $this->brainstorm = Brainstorm::find($id);
            if (!$this->brainstorm) {
                return $this->responseError('Brainstorm not found', 404);
            }
        }

        return true;
    }

}