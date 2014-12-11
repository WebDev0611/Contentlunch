<?php

use LaravelBook\Ardent\Ardent;

class ContentTaskSubscriber extends Ardent 
{
	protected $table = 'content_task_subscribers';

	protected $fillable = [
		'user_id',
		'content_task_id'
	];

	public $timestamps   = false;

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function contentTask()
	{
		return $this->belongsTo('ContentTask');
	}

}