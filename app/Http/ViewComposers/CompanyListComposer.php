<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\UserRepository;
use Session;
use Auth;

use App\Account;
use App\AccountType;

class CompanyListComposer
{
    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'accountsList' => $this->accountsList(),
            'selectedAccount' => $this->selectedAccount(),
        ]);
    }

    private function accountsList()
    {
        if ($this->user->belongsToAgencyAccount()) {
            $agencyAccount = Auth::user()->agencyAccount();
            $childAccounts = $agencyAccount->childAccounts;
            $accounts = $agencyAccount->childAccounts->merge([ $agencyAccount ]);
        } else {
            $accounts = Auth::user()->accounts;
        }

        return $accounts;
    }

    private function selectedAccount()
    {
        if (!Session::has('selected_account_id')) {
            $accountsList = $this->accountsList();
            $selectedAccountId = $accountsList[0]->id;
            Account::selectAccount($accountsList[0]);
        }

        return Account::selectedAccount();
    }
}