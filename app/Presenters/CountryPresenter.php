<?php

namespace App\Presenters;

use App\Country;
use App\Presenters\Helpers\BasePresenter;

class CountryPresenter extends BasePresenter
{
    public static function dropdown()
    {
        $dropdown = [ '' => '-- Select a Country --' ];
        $dropdown += Country::orderBy('priority', 'desc')
            ->orderBy('country_name', 'asc')
            ->pluck('country_name', 'country_code')
            ->toArray();

        return $dropdown;
    }
}
