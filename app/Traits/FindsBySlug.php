<?php

namespace App\Traits;

trait FindsBySlug
{
    public static function findBySlug($slug)
    {
        return static::whereSlug($slug)->first();
    }
}