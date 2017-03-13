<?php

namespace App\Presenters\Helpers;

use Carbon\Carbon;
use Laracasts\Presenter\Presenter;

class BasePresenter extends Presenter
{
    public function isEmptyDate($date)
    {
        return $date === '0000-00-00 00:00:00' || $date === '0000-00-00';
    }

    protected function customDateFormat($dateString, $format = 'm/d/Y')
    {
        return !$this->isEmptyDate($dateString)
            ? (new Carbon($dateString))->format($format)
            : null;
    }
}