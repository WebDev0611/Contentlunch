<?php

namespace App\Services;

use App\Account;
use App\Activity;

class AccountService
{
    protected $account;
    protected $selectedAccount;

    function __construct(Account $account)
    {
        $this->account = $account;
        $this->selectedAccount = Account::selectedAccount() ?: $account;
    }

    public function collaborators()
    {
        return $this->selectedAccount->proxyToParent()
            ->users()
            ->get();
    }

    public function formattedCollaborators()
    {
        return $this->collaborators()
            ->map(function($user) {
                $user->profile_image = $user->present()->profile_image;
                $user->location = $user->present()->location;
                $user->total_tasks = $user->assignedTasks()
                    ->where('account_id', $this->selectedAccount->id)
                    ->where('status', 'open')
                    ->count();

                return $user;
            });
    }

    public function activityFeed()
    {
        return $this->contentActivity()
            ->merge($this->taskActivity())
            ->merge($this->campaignActivity())
            ->sort(function($a, $b) {
                return $a->created_at->lt($b->created_at) ? 1 : -1;
            })
            ->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'user' => $activity->present()->causer,
                    'action' => $activity->present()->action,
                    'subject_type' => $activity->present()->subjectType,
                    'subject_name' => $activity->present()->subjectName,
                    'subject_link' => $activity->present()->subjectLink,
                    'date' => (string) $activity->present()->createdAtFormat('m/d/Y H:i:s'),
                ];
            })
            ->values();
    }

    protected function activityQuery()
    {
        return Activity::with('subject')
            ->select('activity_log.*')
            ->orderBy('activity_log.created_at', 'desc')
            ->where('account_id', $this->selectedAccount->id)
            ->take(10);
    }

    public function contentActivity()
    {
        return $this->activityQuery()
            ->join('contents', 'contents.id', '=', 'activity_log.subject_id')
            ->where('activity_log.subject_type', 'App\Content')
            ->get();
    }

    public function taskActivity()
    {
        return $this->activityQuery()
            ->join('tasks', 'tasks.id', '=', 'activity_log.subject_id')
            ->where('activity_log.subject_type', 'App\Task')
            ->get();
    }

    public function campaignActivity()
    {
        return $this->activityQuery()
            ->join('campaigns', 'campaigns.id', '=', 'activity_log.subject_id')
            ->where('activity_log.subject_type', 'App\Campaign')
            ->get();
    }
}