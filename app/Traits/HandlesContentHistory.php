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
        return $this->contentTasksHistory()
            ->merge($this->contentAdjustments())
            ->sort(function ($adjustmentA, $adjustmentB) {
                return $adjustmentA['date']->lt($adjustmentB['date']) ? 1 : -1;
            });
    }

    protected function contentAdjustments()
    {
        return $this->adjustments
            ->map(function ($adjustmentUser) {
                return [
                    'type' => 'content',
                    'adjustment' => $adjustmentUser,
                    'date' => $adjustmentUser->pivot->created_at,
                ];
            });
    }

    protected function contentTasksHistory()
    {
        return $this->tasks
            ->map(function ($task) {
                return $task->statusAdjustments();
            })
            ->flatten(1)
            ->map(function ($taskAdjustment) {
                return [
                    'type' => 'content_task',
                    'adjustment' => $taskAdjustment,
                    'date' => $taskAdjustment->created_at,
                ];
            });
    }
}
