<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;

class WriterAccessPartialOrderPresenter extends BasePresenter
{
    public function assetType()
    {
        return $this->entity->assetType->name;
    }

    public function description()
    {
        return $this->entity->wordcount . " words " . strtolower($this->assetType);
    }

    public function price()
    {
        return "$" . number_format((float)$this->entity->price, 2, '.', ',');
    }

    public function fee()
    {
        return "$" . number_format((float)$this->entity->fee, 2, '.', ',');
    }

    public function promoDiscount()
    {
        return "$" . number_format((float)$this->entity->promo_discount, 2, '.', ',');
    }
}