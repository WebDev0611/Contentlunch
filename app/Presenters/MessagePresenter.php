<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;

class MessagePresenter extends BasePresenter
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