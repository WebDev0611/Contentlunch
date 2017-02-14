<?php

namespace App\Presenters\Helpers;

use Carbon\Carbon;
use Laracasts\Presenter\Presenter;

class BasePresenter extends Presenter
{
    protected function isEmptyDate($date)
    {
        return $date === '0000-00-00 00:00:00' || $date === '0000-00-00';
    }
}