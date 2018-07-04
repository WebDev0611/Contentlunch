<?php

namespace App\Presenters\Helpers;

use Carbon\Carbon;

trait UpdatedAtPresenter
{
    public function updatedAt()
    {
        if (!$this->isEmptyDate($this->updated_at)) {
            return (new Carbon($this->updated_at))->diffForHumans();
        } else {
            return "--";
        }
    }

    public function updatedAtFormat($format = 'm/d/Y')
    {
        if (!$this->isEmptyDate($this->updated_at)) {
            return (new Carbon($this->updated_at))->format($format);
        } else {
            return "--";
        }
    }
}