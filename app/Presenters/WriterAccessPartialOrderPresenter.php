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
        return "$" . $this->entity->price . ".00";
    }
}