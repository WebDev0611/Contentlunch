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
		$contentTasks = ContentTask::
      whereDueDate(Carbon::today()->addDay()->toDateString())
      ->whereIsComplete(0)
      ->whereDeletedAt(null)
      ->with('user')
      ->get();

    foreach ($contentTasks as $contentTask) {

    	$taskGroup = ContentTaskGroup::whereId($contentTask->content_task_group_id)->first();

			$content = Content::find($taskGroup->content_id);

    	$this->email->sendContentTaskReminder(
    		$contentTask->user->email,
    		$contentTask->user->first_name,
    		$contentTask->name,
    		$contentTask->due_date,
    		$content->title,
        $content->id
    	);
    }

    $campaignTasks = CampaignTask::
      whereDueDate(Carbon::today()->addDay()->toDateString())
      ->whereIsComplete(0)
      ->whereDeletedAt(null)
      ->with('user')
      ->with('campaign')
      ->get();

    foreach ($campaignTasks as $campaignTask) {

    	$this->email->sendCampaignTaskReminder(
    		$campaignTask->user->email,
    		$campaignTask->user->first_name,
    		$campaignTask->name,
    		$campaignTask->due_date,
    		$campaignTask->campaign->title,
        $campaignTask->campaign->id
    	);
    }
	}

}
