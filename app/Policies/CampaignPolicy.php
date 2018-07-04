<?php

namespace App\Policies;

use App\Account;
use App\Campaign;
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

    public function edit(User $user, Campaign $campaign)
    {
        return $campaign->hasCollaborator($user) || $campaign->user_id == $user->id;
    }

    public function destroy(User $user, Campaign $campaign)
    {
        return $campaign->hasCollaborator($user) || $campaign->user_id == $user->id;
    }
}
