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

    public function dropdown()
    {
        $personaDropdown = ['' => '-- Select a Buying Stage --'];
        $personaDropdown += self::select('id', 'name')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'id')
            ->toArray();

        return $personaDropdown;
    }
}
