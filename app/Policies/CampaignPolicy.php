<?php

namespace App\Policies;

use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy extends BasePolicy
{
    use HandlesAuthorization;

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
