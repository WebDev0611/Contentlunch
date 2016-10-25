<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public static function dropdown()
    {
        $dropdown = [ '' => '-- Select a Country --' ];
        $dropdown += Country::orderBy('country_name', 'asc')
            ->lists('country_name', 'country_code')
            ->toArray();

        return $dropdown;
    }
}
