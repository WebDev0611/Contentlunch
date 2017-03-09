<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    public $fillable = [ 'name', 'description' ];

    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    public static function dropdown()
    {
        $personaDropdown = ['' => '-- Select a Persona --'];
        $personaDropdown += Account::selectedAccount()
            ->personas()
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'id')
            ->toArray();

        return $personaDropdown;
    }

    public function __toString()
    {
        return $this->name;
    }
}
