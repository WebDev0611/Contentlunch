<?php

namespace App\Presenters\Helpers;

use Carbon\Carbon;

trait DueDatePresenter
{
    public function dueDate()
    {
        if (!$this->isEmptyDate($this->due_date)) {
            return (new Carbon($this->due_date))->diffForHumans();
        } else {
            return "No due date set";
        }
    }

    public function dueDateFormat($format = 'm/d/Y')
    {
        if (!$this->isEmptyDate($this->due_date)) {
            $carbonDate = gettype($this->due_date) == 'string'
                ? new Carbon($this->due_date)
                : $this->due_date;

            return $carbonDate->format($format);
        } else {
            return "No due date set";
        }
    }
}