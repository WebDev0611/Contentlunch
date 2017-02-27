<?php

namespace App\Policies;

use App\Account;
use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
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
        $campaigns = $user->campaigns()->count();
        $maxCampaigns = Limit::whereName('campaigns')->count();

        return $campaigns < $maxCampaigns;
    }
}
