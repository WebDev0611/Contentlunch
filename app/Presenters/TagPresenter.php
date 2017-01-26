<?php

namespace App\Presenters;

use App\Tag;
use Laracasts\Presenter\Presenter;

class TagPresenter extends Presenter
{
    public static function dropdown()
    {
        return Tag::select('id', 'tag')
            ->orderBy('tag', 'asc')
            ->distinct()
            ->lists('tag', 'id')
            ->toArray();
    }

    public static function json()
    {
        return Tag::select('tag')
            ->orderBy('tag', 'asc')
            ->distinct()
            ->lists('tag')
            ->toJson();
    }
}
