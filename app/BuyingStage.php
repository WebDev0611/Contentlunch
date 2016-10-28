<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuyingStage extends Model
{
    public static function dropdown()
    {
        // - Create Related Content Drop Down Data
        $buyingStageDropdown = ['' => '-- Select a Buying Stage --'];
        $buyingStageDropdown += self::select('id', 'name')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'id')
            ->toArray();

        return $buyingStageDropdown;
    }

    public function __toString()
    {
        return $this->name;
    }
}
