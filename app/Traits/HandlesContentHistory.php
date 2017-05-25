<?php

namespace App\Traits;

use App\Content;
use App\ContentStatus;
use App\Helpers;

trait HandlesContentHistory
{
    /**
     * Human readable column names.
     *
     * @var array
     */
    private static $fieldNames = [
        'id' => 'ID',
        'content_type_id' => 'Content Type',
        'account_id' => 'Account',
        'due_date' => 'Due Date',
        'title' => 'Title',
        'connection_id' => 'Connection',
        'body' => 'Body',
        'buying_stage_id' => 'Buying Stage',
        'persona_id' => 'Persona',
        'campaign_id' => 'Campaign',
        'meta_title' => 'Meta Title',
        'meta_keywords' => 'Meta Keywords',
        'meta_description' => 'Meta Description',
        'content_status_id' => 'Content Status',
        'archived' => 'Archived',
        'ready_published' => 'Ready to be published',
        'published' => 'Published',
        'written' => 'Written',
        'user_id' => 'Author',
        'email_subject' => 'Email Subject',
        'calendar_id' => 'Calendar',
        'created_at' => 'Create at',
        'updated_at' => 'Updated at',
        'mailchimp_settings' => 'Mailchimp Settings',
    ];

    public static function fieldName($key = null)
    {
        return Content::$fieldNames[$key];
    }

    /**
     * Returns a clean value to be used in the content history sidebar.
     *
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    public static function cleanedHistoryContent($key, $value)
    {
        switch ($key) {
            case 'content_type_id':
            case 'connection_id':
            case 'buying_stage_id':
            case 'campaign_id':
            case 'user_id':
                $formattedContent = Helpers::getRelatedContentString($key, $value);
                break;

            case 'body':
                $formattedContent = $value ? strip_tags($value) : '-';
                break;

            case 'due_date':
                $formattedContent = $value == '0000-00-00' ? 'Empty Date' : $value;
                break;

            case 'archived':
            case 'ready_published':
            case 'published':
            case 'written':
                $formattedContent = $value ? 'Yes' : 'No';
                break;

            case 'content_status_id':
                $formattedContent = $value ? ContentStatus::find($value)->name : '-';
                break;

            case 'mailchimp_settings':
                $formattedContent = '-';
                break;

            default:
                $formattedContent = $value ? $value : '-';
                break;
        }

        return $formattedContent;
    }

    public function history()
    {
        return $this->contentHistory()
            ->merge($this->contentTasksHistory())
            ->sort(function ($adjustmentA, $adjustmentB) {
                return $adjustmentA->created_at->lt($adjustmentB->created_at) ? 1 : -1;
            });
    }

    protected function contentHistory()
    {
        return $this->activity()->orderBy('created_at', 'desc')->get();
    }

    public function contentTasksHistory()
    {
        return $this->tasks
            ->map(function ($task) { return $task->activity; })
            ->flatten(1)
            ->filter(function($activity) {
                $properties = collect($activity->properties['attributes']);

                return $activity->description === 'updated' && $properties->has('status');
            })
            ->map(function($activity) {
                $task = $activity->subject;

                switch ($activity->properties['attributes']['status']) {
                    case 'open': $statusVerb = 'opened'; break;
                    default: $statusVerb = $activity->properties['attributes']['status'];
                }

                if ($activity->causer_id && $activity->causer_type == 'App\User') {
                    $userName = $activity->causer->name;
                    $statusDescription = "$userName $statusVerb the task '$task->name'.";
                } else {
                    $statusDescription = "The task '$task->name' was $statusVerb.";
                }

                $activity->statusDescription = $statusDescription;

                return $activity;
            });
    }
}
