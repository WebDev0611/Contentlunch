<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\AccountType;
use App\Subscription;
use App\SubscriptionType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::recent()->paginate(100);

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

    public function storeSubscription(Request $request, Account $account)
    {
        $subscriptionType = SubscriptionType::find($request->input('subscription_type'));

        $account->subscribe(
            $subscriptionType,
            [
                'start_date' => $request->input('start_date'),
                'expiration_date' => $request->input('expiration_date'),
            ]
        );

        return redirect()->route('admin.accounts.edit', $account)->with([
            'flash_message' => sprintf('Added %s subscription to the %s account', $subscriptionType->name, $account->name),
            'flash_message_type' => 'success',
        ]);
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
    public function edit(Account $account = null)
    {
        $data = [
            'account' => $account,
            'accountTypes' => AccountType::pluck('name', 'id'),
            'subscriptions' => $this->accountSubscriptions($account),
            'subscriptionTypes' => $this->subscriptionTypes(),
        ];

        return view('admin.accounts.edit', $data);
    }

    protected function accountSubscriptions($account)
    {
        return $account
            ? $account->subscriptions()->recent()->with('subscriptionType')->get()
            : [];
    }

    protected function subscriptionTypes()
    {
        return SubscriptionType::pluck('name', 'id');
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
