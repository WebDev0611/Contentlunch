<?php

namespace App;

use App\Presenters\ActivityPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    use PresentableTrait;

    protected $presenter = ActivityPresenter::class;
}
