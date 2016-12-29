<?php

namespace App\Presenters;

use Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class ContentPresenter extends Presenter {

    public function dueDate()
    {
        if ($this->due_date !== '0000-00-00') {
            return (new Carbon($this->due_date))->diffForHumans();
        } else {
            return "No due date set";
        }
    }

    public function createdAt()
    {
        return $this->created_at->diffForHumans();
    }

    public function updatedAt()
    {
        return $this->updated_at->diffForHumans();
    }

    public function title()
    {
        return $this->entity->title ? $this->entity->title : 'Untitled Content';
    }

    public function contentIcon()
    {
        $contentIcon = '';

        if ($this->entity->contentType) {
            $contentType = $this->entity->contentType->name;
            $contentTypeSlug = strtolower(explode(' ', $contentType)[0]);
            $contentIcon = 'icon-type-' . $contentTypeSlug;
        }

        return $contentIcon;
    }

    public function contentType()
    {
        return $this->entity->contentType ?
            $this->entity->contentType->name :
            'No content type specified';
    }
}
