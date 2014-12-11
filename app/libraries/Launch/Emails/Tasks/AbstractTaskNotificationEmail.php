<?php namespace Launch\Emails\Tasks;

use \Mail;

abstract class AbstractTaskNotificationEmail {

	protected $initiator,
		      $currentAssignee,
		      $currentTask,
		      $taskParentTitle,
		      $taskParentId,
		      $taskParentType,
              $youHave,
              $heHas,
              $someoneHasText;

  	protected $viewData,
  			  $mailData;

    function __construct(array $config)
    {
    	$this->initiator = $config['initiator'];
    	$this->currentAssignee = $config['currentAssignee'];
    	$this->currentTask = $config['currentTask'];
    	$this->taskParentTitle = $config['taskParentTitle'];
    	$this->taskParentId = $config['taskParentId'];
    	$this->taskParentType = $config['taskParentType'];

        $this->youHave = 'You have';
        $this->heHas = "{$this->initiator['first_name']} {$this->initiator['last_name']} has";

    	$this->viewData = [
    		'currentAssigneeFirstName' => $this->currentAssignee['first_name'],
    		'currentAssigneeLastName' => $this->currentAssignee['last_name'],
    		'initiatorFirstName' => $this->initiator['first_name'],
    		'initiatorLastName' => $this->initiator['last_name'],
            'initiatorId' => $this->initiator['id'],
    		'currentTaskName' => $this->currentTask['name'],
    		'currentTaskDueDate' => $this->formattedDateString($this->currentTask['due_date']),
    		'taskParentTitle' => $this->taskParentTitle,
    		'taskParentType' => $this->taskParentType,
            'taskParentUrl' => $this->generateTaskParentUrl()
    	];

    	$this->mailData = [
    		'toEmail' => $config['toEmail'],
    		'fromEmail' => "do-not-reply@contentlaunch.com",
    		'subject' => null,
    		'template' => null,
    		'timestamp' => $this->currentDateTimeString(),
    		'taskParentType' => $this->taskParentType
    	];
    }

    protected function generateTaskParentUrl()
    {
        switch ($this->taskParentType) {
            case 'Content':
            case 'content':
                return \URL::to("create/content/edit/{$this->taskParentId}");
            
            case 'Campaign':
            case 'campaign':
                return \URL::to("calendar/campaigns/{$this->taskParentId}");

            default:
                return \URL::to('/');
        }
    }

    protected function doSend()
    {
    	$mailData = $this->mailData;

    	Mail::send($mailData['template'], $this->viewData, function ($message) use ($mailData) {
		    $message
		      ->to($mailData['toEmail'])
		      ->from($mailData['fromEmail'], 'Content Launch')
		      ->subject($mailData['subject'] . " on {$mailData['timestamp']}");
		  });
    }

    protected function currentDateTimeString($zone = 'PST')
    {
    	$date = \Carbon\Carbon::now($zone);
    	return $date->toDayDateTimeString();
    }

    protected function formattedDateString($date)
    {
    	$date = new \Carbon\Carbon($date);
		return $date->toFormattedDateString();
    }
}