<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;

class ContentMessagePresenter extends BasePresenter
{
    public function sender()
    {
        $sender = $this->entity->sender;

        return [
            'name' => $sender->name,
            'profile_image' => $sender->present()->profile_image(),
        ];
    }
}