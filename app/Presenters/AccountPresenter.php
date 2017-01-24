<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class AccountPresenter extends Presenter
{
    public function tagsDropdown()
    {
        return $this->entity
            ->tags()
            ->select('id', 'tag')
            ->orderBy('tag', 'asc')
            ->distinct()
            ->lists('tag', 'id')
            ->toArray();
    }

    public function tagsJson()
    {
        return $this->entity
            ->tags()
            ->select('tag')
            ->orderBy('tag', 'asc')
            ->distinct()
            ->lists('tag')
            ->toJson();
    }
}
