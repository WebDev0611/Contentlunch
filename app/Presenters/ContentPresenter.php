<?php

namespace App\Presenters;

use App\Presenters\Helpers\BasePresenter;
use App\Presenters\Helpers\CreatedAtPresenter;
use App\Presenters\Helpers\DueDatePresenter;
use App\Presenters\Helpers\UpdatedAtPresenter;
use Carbon\Carbon;

class ContentPresenter extends BasePresenter
{
    use UpdatedAtPresenter, CreatedAtPresenter, DueDatePresenter;

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

    public function tagsJson()
    {
        return $this->entity
            ->tags()
            ->pluck('tag')
            ->toJson();
    }

    public function tags()
    {
        return $this->entity->tags()->pluck('tag')->implode(', ');
    }

    public function contentStatusIcon()
    {
        $content = $this->entity;
        $status = 1;

        if ($content->published == '1') {
            $status = 4;
        } else if ($content->ready_published == '1') {
            $status = 3;
        } else if ($content->written == '1') {
            $status = 2;
        }

        return $status;
    }
}
