<?php

namespace App\Presenters;

use App\ContentType;
use App\Presenters\Helpers\BasePresenter;

class ContentTypePresenter extends BasePresenter
{
    public static function dropdown()
    {
        return ContentType::select('id','name')
            ->where('active', true)
            ->orderBy('name', 'asc')
            ->distinct()
            ->pluck('name', 'id')
            ->toArray();
    }
}