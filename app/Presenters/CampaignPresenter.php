<?php

namespace App\Presenters;

use Illuminate\Support\Facades\Auth;
use Laracasts\Presenter\Presenter;

class CampaignPresenter extends Presenter
{
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