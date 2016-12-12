<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;

use App\Account;
use App\AccountType;

class AgencyController extends Controller
{
    public function index()
    {
        $accounts = collect([ Auth::user()->agencyAccount() ])
            ->merge(Auth::user()->agencyAccount()->childAccounts);

        return view('agency.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validation = $this->validator($request->all());

        if ($validation->fails()) {
            return redirect()->route('agencyIndex')->with('errors', $validation->errors());
        }

        $agencyAccount = Auth::user()->agencyAccount();
        $newAccount = Account::create([
            'name' => $request->input('account_name'),
            'account_type_id' => AccountType::COMPANY,
            'parent_account_id' => $agencyAccount->id
        ]);

        return redirect()->route('agencyIndex')->with([
            'flash_message' => 'Sub-Account created successfully.',
            'flash_message_type' => 'success'
        ]);
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'account_name' => 'required'
        ]);
    }
}