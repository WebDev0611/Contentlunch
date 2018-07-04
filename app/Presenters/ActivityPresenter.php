<?php

namespace App\Presenters;

use App\Campaign;
use App\Content;
use App\Presenters\Helpers\BasePresenter;
use App\Presenters\Helpers\CreatedAtPresenter;
use App\Task;

class ActivityPresenter extends BasePresenter
{
    use CreatedAtPresenter;

    public function causer()
    {
        $user = $this->entity->causer;

        $user->profile_image = $user->present()->profile_image;
        $user->location = $user->present()->location;

        return $user;
    }

    public function subjectName()
    {
        return $this->entity->subject->present()->title;
    }

    public function subjectType()
    {
        switch ($this->entity->subject_type) {
            case Task::class: return 'task';
            case Content::class: return 'content';
            case Campaign::class: return 'campaign';
            default: return 'resource';
        }
    }

    public function subjectLink()
    {
        switch ($this->entity->subject_type) {
            case Task::class: return route('tasks.edit', $this->entity->subject_id);
            case Content::class: return route('editContent', $this->entity->subject_id);
            case Campaign::class: return route('campaigns.edit', $this->entity->campaign_id);
            default: return '';
        }
    }

    public function action()
    {
        switch ($this->entity->subject_type) {
            case Task::class: return $this->taskDescription();
            default: return $this->entity->description;
        }
    }

    protected function taskDescription()
    {
        $properties = collect($this->entity->properties['attributes']);

        if ($this->entity->description === 'updated' && $properties->has('status')) {
            $map = ['open' => 'opened', 'closed' => 'closed'];

            return $map[$properties['status']];
        }

        return $this->entity->description;
    }
}