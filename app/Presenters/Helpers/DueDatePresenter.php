<?php

namespace App\Presenters\Helpers;

use Carbon\Carbon;

trait DueDatePresenter
{
    public function dueDate()
    {
        if ($this->due_date !== '0000-00-00') {
            return (new Carbon($this->due_date))->diffForHumans();
        } else {
            return "No due date set";
        }
    }

    public function dueDateFormat($format = 'm/d/Y')
    {
        if ($this->due_date !== '0000-00-00') {
            return (new Carbon($this->due_date))->format($format);
        } else {
            return "No due date set";
        }
    }
}