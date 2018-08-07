<?php

namespace App\Transformers;

use App\Campaign;
use League\Fractal\TransformerAbstract;

class CampaignTransformer extends TransformerAbstract
{
    public function transform(Campaign $campaign)
    {
        return [
            'title' => $campaign->present()->title,
            'user_id' => $campaign->user_id,
            'account_id' => $campaign->account_id,
            'status' => $campaign->status,
            'campaign_type_id' => $campaign->campaign_type_id,
            'start_date' => (string) $campaign->start_date,
            'end_date' => (string) $campaign->end_date,
            'is_recurring' => $campaign->is_recurring,
            'description' => $campaign->description,
            'goals' => $campaign->goals,
            'interval' => $campaign->interval,
            'created_at' => (string) $campaign->created_at,
            'updated_at' => (string) $campaign->updated_at,
        ];
    }
}