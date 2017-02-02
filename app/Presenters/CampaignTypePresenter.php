<?php

namespace App\Presenters;

use App\CampaignType;
use Laracasts\Presenter\Presenter;

class CampaignTypePresenter extends Presenter
{
    public static function dropdown()
    {
        $campaignTypeDropdown = ['' => '-- Select Campaign Type --'];
        $campaignTypeDropdown += CampaignType::select('id','name')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'id')
            ->toArray();

        return $campaignTypeDropdown;
    }
}