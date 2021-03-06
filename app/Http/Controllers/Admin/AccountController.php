<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\AccountType;
use App\SubscriptionType;
use App\Traits\Redirectable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AccountController extends Controller
{
    use Redirectable;

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
        $data = collect($request->all())
            ->only('start_date', 'expiration_date')
            ->toArray();

        $account->subscribe($subscriptionType, $data);

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
     * @param  \Illuminate\Http\Request $request
     * @param Account $account
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Account $account)
    {
        $account->update($request->all());

        return redirect()->route('admin.accounts.edit', $account)->with([
            'flash_message' => 'Account updated',
            'flash_message_type' => 'success',
        ]);
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
