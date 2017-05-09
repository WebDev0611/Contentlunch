<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuyingStage extends Model
{
    public $fillable = [ 'name', 'description' ];

    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    public static function dropdown()
    {
        $buyingStageDropdown = ['' => '-- Select a Buying Stage --'];
        $buyingStageDropdown += Account::selectedAccount()
            ->buyingStages()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->distinct()
            ->pluck('name', 'id')
            ->toArray();

        return $buyingStageDropdown;
    }

    public function __toString()
    {
        return $this->name;
    }
}
