<?php

namespace App;

use App\Presenters\CountryPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Country extends Model
{
    use PresentableTrait;

    protected $presenter = CountryPresenter::class;
}
