<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;

class UserPresenter extends BasePresenter
{
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

    public function accountList()
    {
        return $this->entity->accounts()
            ->orderBy('accounts.created_at')
            ->get()
            ->pluck('name')
            ->implode(', ');
    }
}