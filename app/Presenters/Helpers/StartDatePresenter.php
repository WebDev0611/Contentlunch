<?php

namespace App\Presenters\Helpers;

use Carbon\Carbon;

trait StartDatePresenter
{
    public function startDate()
    {
        if (!$this->isEmptyDate($this->start_date)) {
            return (new Carbon($this->start_date))->diffForHumans();
        } else {
            return "No start date set";
        }
    }

    public function startDateFormat($format = 'm/d/Y')
    {
        if (!$this->isEmptyDate($this->start_date)) {
            return (new Carbon($this->start_date))->format($format);
        } else {
            return "No start date set";
        }
    }
}