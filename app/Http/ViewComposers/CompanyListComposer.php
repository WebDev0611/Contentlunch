<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Repositories\UserRepository;

use App\Account;
use App\AccountType;
use Auth;

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
        $selectedAccountId = session('selected_account_id');

        if (!$selectedAccountId) {
            $accountsList = $this->accountsList();
            $selectedAccountId = $accountsList[0]->id;
            session([ 'selected_account_id' => $selectedAccountId ]);
        }

        return Account::find($selectedAccountId);
    }
}