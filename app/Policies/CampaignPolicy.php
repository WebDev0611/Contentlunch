<?php

namespace App\Policies;

use App\Account;
use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function before (User $user)
    {
        if (!Account::selectedAccount()->activeSubscriptions()->isEmpty())
        {
            return true;
        }
    }

    public function create(User $user)
    {
        if (!$this->account->hasLimit('campaigns')) {
            return true;
        }

        $campaigns = $user->campaigns()->count();
        $maxCampaigns = $this->account->limit('campaigns');

        return $campaigns < $maxCampaigns;
    }
}
