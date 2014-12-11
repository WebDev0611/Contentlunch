<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \Carbon\Carbon;
use Launch\Repositories\EmailRepository;

class TaskReminderCommand extends ScheduledCommand {

	protected $name = 'cl:taskreminder';

	protected $description = 'Sends reminder emails on tasks that are due tomorrow';

	protected $email;

	public function __construct()
	{
		parent::__construct();
		$this->email = new EmailRepository;
	}

	public function schedule(Schedulable $scheduler)
	{
		return $scheduler
            ->daily()
            ->hours(16)
            ->minutes(17);
	}

	public function fire()
	{
    \Log::info('Running task reminder');

		$contentTasks = ContentTask::
      whereDueDate(Carbon::today()->addDay()->toDateString())
      ->whereIsComplete(0)
      ->with('user')
      ->get();

    foreach ($contentTasks as $contentTask) {

    	$taskGroup = ContentTaskGroup::whereId($contentTask->content_task_group_id)->first();

			$content = Content::find($taskGroup->content_id);

      $subscribers = false;

      $subs = $contentTask->subscribers;
      if ($subs) {
        foreach ($subs as $sub) {
          $subscribers[] = $sub->user;
        }
      }

      // Older tasks do not have a notion of subscribers
      // so send email to task assignee if no
      // subscribers exist
      if ($subscribers) {
        foreach ($subscribers as $subscriber) {
          $this->sendContentTaskReminder(
            $contentTask,
            $content,
            $subscriber
          );
        }
      } else {
        $this->sendContentTaskReminder(
            $contentTask,
            $content,
            $contentTask->user
          );
      }
    }

    $campaignTasks = CampaignTask::
      whereDueDate(Carbon::today()->addDay()->toDateString())
      ->whereIsComplete(0)
      ->with('user')
      ->with('campaign')
      ->get();

    foreach ($campaignTasks as $campaignTask) {

      $subscribers = false;

      $subs = $campaignTask->subscribers;
      if ($subs) {
        foreach ($subs as $sub) {
          $subscribers[] = $sub->user;
        }
      }

      // Older tasks do not have a notion of subscribers
      // so send email to task assignee if no
      // subscribers exist
      if ($subscribers) {
        foreach ($subscribers as $subscriber) {
          $this->sendCampaignTaskReminder(
            $campaignTask,
            $campaignTask->campaign,
            $subscriber
          );
        }
      } else {
        $this->sendCampaignTaskReminder(
            $campaignTask,
            $campaignTask->campaign,
            $campaignTask->user
          );
      }
    }
	}

  public function sendContentTaskReminder($task, $content, $user)
  {
    $this->email->sendContentTaskReminder(
        $user->email,
        $task->user->first_name,
        $task->user->last_name,
        $task->name,
        $task->due_date,
        $content->title,
        $content->id
      );
  }

  public function sendCampaignTaskReminder($task, $campaign, $user)
  {
    $this->email->sendCampaignTaskReminder(
        $user->email,
        $task->user->first_name,
        $task->user->last_name,
        $task->name,
        $task->due_date,
        $campaign->title,
        $campaign->id
      );
  }

}
