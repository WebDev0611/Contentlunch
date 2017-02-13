<?php namespace App;

use App\Presenters\CampaignTypePresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class CampaignType extends Model
{
    use PresentableTrait;

    protected $presenter = CampaignTypePresenter::class;
}