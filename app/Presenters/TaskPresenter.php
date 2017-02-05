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

    public function dueDateFormat()
    {
        if (!$this->isEmptyDate($this->due_date)) {
            return (new Carbon($this->due_date))->format('m/d/Y');
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

    public function dueDateFormat($format = 'd/m/y')
    {
        if (!$this->isEmptyDate($this->due_date)) {
            return (new Carbon($this->due_date))->format($format);
        } else {
            return "No due date set";
        }
    }

    public function startDateFormat($format = 'd/m/y')
    {
        if (!$this->isEmptyDate($this->start_date)) {
            return (new Carbon($this->start_date))->format($format);
        } else {
            return "No due date set";
        }
    }

    public function updatedAt()
    {
        if (!$this->isEmptyDate($this->updated_at)) {
            return (new Carbon($this->updated_at))->diffForHumans();
        } else {
            return "--";
        }
    }

    public function createdAt()
    {
        if (!$this->isEmptyDate($this->created_at)) {
            return (new Carbon($this->created_at))->diffForHumans();
        } else {
            return "--";
        }
    }

    protected function isEmptyDate($date)
    {
        return $date === '0000-00-00 00:00:00';
    }

}