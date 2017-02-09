<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;
use App\Presenters\Helpers\CreatedAtPresenter;
use App\Presenters\Helpers\DueDatePresenter;
use App\Presenters\Helpers\StartDatePresenter;
use App\Presenters\Helpers\UpdatedAtPresenter;
use Carbon\Carbon;

class TaskPresenter extends BasePresenter
{
    use DueDatePresenter, StartDatePresenter, UpdatedAtPresenter, CreatedAtPresenter;
}