<?php

namespace App\Presenters;

use Carbon\Carbon;
use Laracasts\Presenter\Presenter;

class TaskPresenter extends Presenter {

    public function dueDate()
    {
        if (!$this->isEmptyDate($this->due_date)) {
            return (new Carbon($this->due_date))->diffForHumans();
        } else {
            return "No due date set";
        }
    }

    public function startDate()
    {
        if (!$this->isEmptyDate($this->start_date)) {
            return (new Carbon($this->start_date))->diffForHumans();
        } else {
            return "No start date set";
        }
    }

    protected function isEmptyDate($date)
    {
        return $date === '0000-00-00 00:00:00';
    }

}