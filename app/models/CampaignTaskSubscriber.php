<?php

use LaravelBook\Ardent\Ardent;

class CampaignTaskSubscriber extends Ardent 
{
	protected $table = 'campaign_task_subscribers';

	protected $fillable = [
		'user_id',
		'campaign_task_id'
	];

	public $timestamps   = false;

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function contentTask()
	{
		return $this->belongsTo('CampaignTask');
	}

}