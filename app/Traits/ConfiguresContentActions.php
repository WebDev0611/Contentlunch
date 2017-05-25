<?php

namespace App\Traits;

use App\ContentStatus;

trait ConfiguresContentActions
{
    /**
     * Configures the flags ready_published, written and published according to the
     * $action string
     */
    public function configureAction($action = null)
    {
        $status = ContentStatus::BEING_WRITTEN;

        switch ($action) {
            case 'ready_to_publish': $status = ContentStatus::READY; break;
            case 'publish': $status = ContentStatus::PUBLISHED; break;
            case 'archived': $status = ContentStatus::ARCHIVED; break;
            default: break;
        }

        $this->status()->associate($status)->save();
    }

    public function scopeReadyToPublish($query)
    {
        return $query->where('content_status_id', ContentStatus::READY);
    }

    public function scopeWritten($query)
    {
        return $query->where('content_status_id', ContentStatus::BEING_WRITTEN);
    }

    public function scopePublished($query)
    {
        return $query->where('content_status_id', ContentStatus::PUBLISHED);
    }

    public function scopeArchived($query)
    {
        return $query->where('content_status_id', ContentStatus::ARCHIVED);
    }

    public function scopeCurrent ($query)
    {
        return $query->whereIn('content_status_id', [ContentStatus::READY, ContentStatus::BEING_WRITTEN]);
    }

    public function setReadyPublished()
    {
        $this->configureAction('ready_to_publish');
    }

    public function setWritten()
    {
        $this->configureAction('written_content');
    }

    public function setPublished()
    {
        $this->configureAction('publish');
    }

    public function setArchived()
    {
        $this->configureAction('archived');
    }
}