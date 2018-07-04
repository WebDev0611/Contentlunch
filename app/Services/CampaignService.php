<?php

namespace App\Services;

use App\Account;
use App\Campaign;
use Illuminate\Support\Facades\Auth;

class CampaignService
{
    protected $selectedAccount;
    protected $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->selectedAccount = Account::selectedAccount() ?: $campaign->account;
        $this->campaign = $campaign;
    }

    public function campaignList()
    {
        $campaigns = Auth::user()->isGuest()
            ? Auth::user()->guestCampaigns()
            : $this->selectedAccount->campaigns();

        return $campaigns
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();
    }
}