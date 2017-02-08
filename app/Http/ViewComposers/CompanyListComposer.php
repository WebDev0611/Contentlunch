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
            $agencyAccounts = Auth::user()
                ->accounts()
                ->where('account_type_id', AccountType::AGENCY)
                ->get();

            $childAccounts = $agencyAccounts
                ->map(function($agencyAccount) {
                    return $agencyAccount->childAccounts;
                })
                ->flatten(1);

            $accounts = $agencyAccounts->merge($childAccounts);
        } else {
            $accounts = Auth::user()->accounts;
        }

        return $accounts;
    }

    private function selectedAccount()
    {
        if (!Auth::user()->selectedAccount) {
            $accountsList = $this->accountsList();
            $selectedAccountId = $accountsList[0]->id;
            Account::selectAccount($accountsList[0]);
        }

        return Account::selectedAccount();
    }
}