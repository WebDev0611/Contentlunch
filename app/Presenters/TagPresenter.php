<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;
use App\Tag;

class TagPresenter extends BasePresenter
{
    public static function dropdown()
    {
        return Tag::select('id', 'tag')
            ->orderBy('tag', 'asc')
            ->distinct()
            ->pluck('tag', 'id')
            ->toArray();
    }

    public static function json()
    {
        return Tag::select('tag')
            ->orderBy('tag', 'asc')
            ->distinct()
            ->pluck('tag')
            ->toJson();
    }
}
