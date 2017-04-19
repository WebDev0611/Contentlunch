<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\AccountType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class AccountController extends Controller
{
    protected $account;
    protected $accountType;

    public function __construct(Account $account, AccountType $accountType)
    {
        $this->account = $account;
        $this->accountType = $accountType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = $this->account->recent()->paginate(100);

        return view('admin.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Account $account
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Account $account)
    {
        $users = $account->users()->recent()->get();

        return view('admin.accounts.show', compact('account', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Account $account
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Account $account)
    {
        return view('admin.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
