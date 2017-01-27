<?php

namespace App\Presenters\Helpers;

use Carbon\Carbon;

trait CreatedAtPresenter
{
    public function createdAt()
    {
        if (!$this->isEmptyDate($this->created_at)) {
            return (new Carbon($this->created_at))->diffForHumans();
        } else {
            return "--";
        }
    }

    public function createdAtFormat($format = 'm/d/Y')
    {
        if (!$this->isEmptyDate($this->created_at)) {
            return (new Carbon($this->created_at))->format($format);
        } else {
            return "--";
        }
    }
}