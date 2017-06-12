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
        $transformer = $this->getTransformerInstance($activity->subject_type);
        return $this->item($activity->subject, $transformer);
    }

    public function getTransformerInstance($subjectType)
    {
        $className = 'App\\Transformers\\' . collect(explode('\\', $subjectType))->last() . 'Transformer';

        return new $className;
    }
}