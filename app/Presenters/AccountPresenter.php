<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;

class AccountPresenter extends BasePresenter
{
    public function account_image()
    {
        return $this->entity->account_image ?
            $this->entity->account_image :
            \App\Account::DEFAULT_ACCOUNT_IMAGE;
    }

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
