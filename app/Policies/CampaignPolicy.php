<?php

namespace App\Policies;

use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $campaigns = $user->campaigns()->count();
        $maxCampaigns = Limit::whereName('campaigns')->count();

        return $campaigns < $maxCampaigns;
    }
}
