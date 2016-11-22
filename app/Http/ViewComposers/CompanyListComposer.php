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
        //
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
        $agencyAccount = Auth::user()->agencyAccount();
        $childAccounts = $agencyAccount->childAccounts;

        return $agencyAccount->childAccounts->merge([ $agencyAccount ]);
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