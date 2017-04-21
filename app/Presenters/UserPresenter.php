<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;
use App\Presenters\Helpers\CreatedAtPresenter;
use App\Presenters\Helpers\UpdatedAtPresenter;

class UserPresenter extends BasePresenter
{
    use UpdatedAtPresenter, CreatedAtPresenter;

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

    public function lastLoginFormat($format = 'm/d/Y H:i:s')
    {
        $login = $this->entity->logins()->recent()->first();

        return $login
            ? $login->present()->createdAtFormat($format)
            : '--';
    }
}