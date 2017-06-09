<?php

namespace App\Transformers;

use App\Content;
use League\Fractal\TransformerAbstract;

class ContentTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'status',
        'user',
    ];

    public function transform(Content $content)
    {
        return [
            'id' => $content->id,
            'title' => $content->present()->title,
            'due_date_human' => $content->present()->dueDate,
            'content_type_id' => $content->content_type_id,
            'account_id' => $content->account_id,
            'due_date' => (string) $content->due_date,
            'connection_id' => $content->connection_id,
            'content_icon' => $content->present()->contentIcon,
            'content_type' => $content->present()->contentType,
            'link' => $content->present()->link,
            'body' => $content->body,
            'buying_stage_id' => $content->buying_stage_id,
            'persona_id' => $content->persona_id,
            'campaign_id' => $content->campaign_id,
            'meta_title' => $content->meta_title,
            'meta_keywords' => $content->meta_keywords,
            'meta_description' => $content->meta_description,
            'content_status_id' => $content->content_status_id,
            'user_id' => $content->user_id,
            'email_subject' => $content->email_subject,
            'calendar_id' => $content->calendar_id,
            'mailchimp_settings' => $content->mailchimp_settings,
            'custom_content_type_id' => $content->custom_content_type_id,
            'created_at' => (string) $content->created_at,
            'updated_at' => (string) $content->updated_at,
            'created_at_diff' => $content->present()->createdAt,
            'updated_at_diff' => $content->present()->updatedAt,
            'created_at_format' => $content->present()->createdAtFormat,
            'updated_at_format' => $content->present()->updatedAtFormat,
        ];
    }

    public function includeStatus(Content $content)
    {
        return $this->item($content->status, new ContentStatusTransformer);
    }

    public function includeUser(Content $content)
    {
        return $this->item($content->author, new UserTransformer);
    }
}