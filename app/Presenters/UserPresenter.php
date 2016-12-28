<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter {

    public function profile_image()
    {
        return $this->entity->profile_image ?
            $this->entity->profile_image :
            \App\User::DEFAULT_PROFILE_IMAGE;
    }

    public function location()
    {
        $location = $this->city ?: '';
        $location .= $this->city && $this->country ? ', ' : '';
        $location .= $this->country ? $this->country->country_name : '';

        return $location;
    }
}