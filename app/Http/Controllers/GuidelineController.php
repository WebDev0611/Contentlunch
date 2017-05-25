<?php

namespace App\Http\Controllers;

use App\Account;
use App\Guideline;
use App\Http\Requests;
use App\Traits\Redirectable;
use Illuminate\Http\Request;

class GuidelineController extends Controller
{
    use Redirectable;

    private $guideline;
    private $selectedAccount;

    public function __construct(Guideline $guideline)
    {
        $this->guideline = $guideline;
        $this->selectedAccount = Account::selectedAccount();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $guideline = $this->selectedAccount->guidelines()->firstOrCreate([]);

        $guideline->update($request->all());

        return $this->success('content_settings.index', 'Your company strategy was updated successfully.');
    }
}
