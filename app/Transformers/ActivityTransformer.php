<?php

namespace App\Transformers;

use App\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'user',
        'subject',
    ];

    public function transform(Activity $activity)
    {
        return [
            'id' => $activity->id,
            'user' => $activity->present()->causer,
            'action' => $activity->present()->action,
            'subject_type' => $activity->present()->subjectType,
            'subject_name' => $activity->present()->subjectName,
            'subject_link' => $activity->present()->subjectLink,
            'date' => (string) $activity->present()->createdAtFormat('m/d/Y H:i:s'),
        ];
    }

    public function includeUser(Activity $activity)
    {
        return $activity->user ? $this->item($activity->user, new UserTransformer) : null;
    }

    public function includeSubject(Activity $activity)
    {
        switch ($activity->subject_type) {
            case \App\Task::class: return $this->item($activity->subject, new TaskTransformer);
            case \App\Content::class: return $this->item($activity->subject, new ContentTransformer);
            default: return null;
        }
    }
}