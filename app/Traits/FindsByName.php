<?php

namespace App\Traits;

trait FindsByName
{
    public static function findByName($name)
    {
        return static::whereName($name)->first();
    }
}