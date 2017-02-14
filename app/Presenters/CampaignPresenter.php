<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;
use App\Presenters\Helpers\EndDatePresenter;
use App\Presenters\Helpers\StartDatePresenter;
use App\Presenters\Helpers\UpdatedAtPresenter;
use Illuminate\Support\Facades\Auth;

class CampaignPresenter extends BasePresenter
{
    use StartDatePresenter, EndDatePresenter, UpdatedAtPresenter;

    public function collaboratorsIDs()
    {
        return $this->entity->collaborators->pluck('id')->implode(',');
    }

    public static function dropdown($user = null)
    {
        $user = $user ?: Auth::user();

        $campaignDropdown = ['' => '-- Select a Campaign --'];
        $campaignDropdown += $user->campaigns()
            ->select('id', 'title')
            ->where('status', 1)
            ->orderBy('title', 'asc')
            ->distinct()
            ->lists('title', 'id')
            ->toArray();

        return $campaignDropdown;
    }
}