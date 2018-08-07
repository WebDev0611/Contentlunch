<?php

namespace App\Presenters\Helpers;

use Carbon\Carbon;

trait EndDatePresenter
{
    public function endDate()
    {
        if (!$this->isEmptyDate($this->end_date)) {
            return (new Carbon($this->end_date))->diffForHumans();
        } else {
            return "No start date set";
        }
    }

    public function endDateFormat($format = 'm/d/Y')
    {
        if (!$this->isEmptyDate($this->end_date)) {
            return (new Carbon($this->end_date))->format($format);
        } else {
            return "No start date set";
        }
    }
}