<?php

namespace App\Transformers;

use App\ContentStatus;
use League\Fractal\TransformerAbstract;

class ContentStatusTransformer extends TransformerAbstract
{
    public function transform(ContentStatus $status)
    {
        return [
            'id' => $status->id,
            'name' => $status->name,
            'slug' => $status->slug,
            'created_at' => (string) $status->created_at,
            'updated_at' => (string) $status->updated_at,
        ];
    }
}