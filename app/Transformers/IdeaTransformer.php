<?php

namespace App\Transformers;

use App\Idea;
use League\Fractal\TransformerAbstract;

class IdeaTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user',
    ];

    public function transform(Idea $idea)
    {
        return [
            'id' => $idea->id,
            'user_id' => $idea->user_id,
            'account_id' => $idea->account_id,
            'name' => $idea->name,
            'text' => $idea->text,
            'tags' => $idea->tags,
            'created_at' => (string) $idea->created_at,
            'updated_at' => (string) $idea->updated_at,
            'created_diff' => $idea->created_at->diffForHumans(),
            'updated_diff' => $idea->updated_at->diffForHumans(),
            'status' => $idea->status,
            'calendar_id' => $idea->calendar_id,
        ];
    }

    public function includeUser(Idea $idea)
    {
        return $this->item($idea->user, new UserTransformer);
    }
}