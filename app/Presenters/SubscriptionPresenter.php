<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;
use App\Presenters\Helpers\StartDatePresenter;

class SubscriptionPresenter extends BasePresenter
{
    use StartDatePresenter;

    public function expirationDateFormat($format = 'm/d/Y')
    {
        return $this->customDateFormat($this->entity->expiration_date, $format) ?: "-";
    }

    public function price()
    {
        return '$' . number_format($this->entity->subscriptionType->price);
    }
}