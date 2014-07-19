<?php

use LaravelBook\Ardent\Ardent;
use webignition\InternetMediaType\Parser\TypeParser;

class Content extends Ardent {

  protected $table = 'content';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'title', 'body', 'account_id', 'connection_id',
    'buying_stage', 'persona',
    'secondary_buying_stage', 'secondary_persona', 'status',
    'archived', 'concept'
  ];

  public static $rules = [
    'title' => 'required',
    'account_id' => 'required',
    'content_type_id' => 'required',
    'user_id' => 'required'
  ];

  /**
   * Override ardent's validate method and add our own custom validation for content type / upload type
   * @return boolean True if valid, false if validation fails
   */
  public function validate(array $rules = [], array $customMessages = [])
  {
    // Based on the content type, make sure the main upload file is a valid type
    if ( ! empty($this->content_type_id) && ! empty($this->upload_id)) {
      $contentType = $this->content_type()->first();
      $upload = $this->upload()->first();

      // Get the internet media type for the upload
      $parser = new TypeParser;
      $type = $parser->parse($upload->mimetype);

      // Setup types to accept for content types
      $valid = [
        'audio-recording' => ['audio'],
        'photo' => ['image'],
        'video' => ['video'],
      ];
      if (isset($valid[$contentType->key])) {
        if ( ! in_array($type, $valid[$contentType->key])) {
          $this->validationErrors->add('upload', 'Invalid upload type: '. $type .' for content type: '. $contentType->name);
          return false;
        }
      }
    }
    // Force direct uploads to be status of 4
    // And upload_id must be present
    if ( ! empty($this->content_type_id)) {
      $type = ContentType::find($this->content_type_id);
      if ($type->key == 'direct-upload') {
        if ( ! $this->upload_id) {
          $this->validationErrors->add('upload', 'Missing required upload_id for Direct Upload');
          return false;
        }
        if ($this->status != 4) {
          $this->validationErrors->add('status', 'Direct upload must be set to status of 4');
          return false;
        }
      }
    }
    return parent::validate($rules, $customMessages);
  }

  public function activities()
  {
    return $this->hasMany('ContentActivity');
  }

  public function campaign()
  {
    return $this->belongsTo('Campaign');
  }

  public function collaborators()
  {
    return $this->belongsToMany('User', 'content_collaborators', 'content_id', 'user_id')->withTimestamps();
  }

  public function comments()
  {
    return $this->hasMany('ContentComment', 'content_id', 'id')->with('user')->with('guest');
  }

  public function content_type()
  {
    return $this->belongsTo('ContentType');
  }

  /**
   * This is the main file that corresponds to the content type
   */
  public function upload()
  {
    return $this->hasOne('Upload', 'id', 'upload_id');
  }

  /**
   * These are extra files attached to the content
   */
  public function uploads()
  {
    return $this->belongsToMany('Upload', 'content_uploads', 'content_id', 'upload_id')->withTimestamps();
  }

  public function task_groups()
  {
    return $this->hasMany('ContentTaskGroup', 'content_id', 'id')->with('tasks');
  }

  public function guest_collaborators()
  {
    return $this->hasMany('GuestCollaborator', 'content_id', 'id')->where('content_type', 'content');
  }

  public function account()
  {
    return $this->belongsTo('Account');
  }

  public function account_connections()
  {
    return $this->belongsToMany('AccountConnection', 'content_account_connections', 'content_id', 'account_connection_id')
      ->withTimestamps()
      ->with('connection');
  }

  public function related()
  {
    return $this->belongsToMany('Content', 'content_related', 'content_id', 'related_content_id')->withTimestamps();
  }

  public function tags()
  {
    return $this->hasMany('ContentTag', 'content_id', 'id');
  }

  public function user()
  {
    return $this->belongsTo('User')->with('image');
  }

  public static function boot()
  {
    parent::boot();

    static::created(function ($content) {
      // every content has 4 (and only 4) task groups (one for each status/step)
      for ($i = 1; $i <= 4 ; $i++) { 
        $task_group = new ContentTaskGroup();
        $task_group->status = $i;
        $task_group->due_date = date('Y-m-d', time() + 60 * 60 * 24 * 7 * $i);
        $task_group->content_id = $content->id;
        $content->task_groups()->save($task_group);
      }

      return $content;
    });

    static::updated(function ($content) {
      // Get properties that were changed
      $dirty = $content->getDirty();
      if ($dirty) {
        $user = Confide::user();
        foreach ($dirty as $key => $newValue) {
          $activity = null;
          $origValue = $content->getOriginal($key);
          switch ($key) {
            // Archived?
            case 'archived':
              if ($newValue) {
                DB::table('content')
                  ->where('id', $content->id)
                  ->update(['archive_date' => new \DateTime]);
                $activity = 'Archived';
              }
            break;
            case 'body':
              $activity = 'Edited content';
            break;
            // If status changed, log as an activity
            // Also store the date of the change on the content record
            case 'status':
              if ($origValue == 0 && $newValue == 1) {
                DB::table('content')
                  ->where('id', $content->id)
                  ->update(['convert_date' => new \DateTime]);
                $activity = 'Converted Concept to Content';
              } elseif ($newValue == 2) {
                DB::table('content')
                  ->where('id', $content->id)
                  ->update(['submit_date' => new \DateTime]);
                $activity = 'Submitted for Review';
              } elseif ($newValue == 3) {
                DB::table('content')
                  ->where('id', $content->id)
                  ->update(['approval_date' => new \DateTime]);
                $activity = 'Approved';
              } elseif ($newValue == 4) {
                // Content has moved to the "Launch" phase
                DB::table('content')
                  ->where('id', $content->id)
                  ->update(['launch_date' => new \DateTime]);
                $activity = 'Launched';
              } elseif ($newValue == 5) {
                DB::table('content')
                  ->where('id', $content->id)
                  ->update(['promote_date' => new \DateTime]);
                $activity = 'Promoted';
              }
            break;
            // Author changed?
            case 'user_id':
              $author = User::find($newValue);
              $activity = 'Assigned '. strtoupper($author->first_name .' '. $author->last_name) .' as the Author';
            break;
          }
          if ( ! empty($activity)) {
            $activity = new ContentActivity([
              'user_id' => $user->id,
              'content_id' => $content->id,
              'activity' => $activity
            ]);
            $activity->save();
          }
        }
      }
    });
/*
    static::validating(function ($content) {
      $content->setAttribute('errors', [['upload' => 'Invalid upload file type']]);
      return false;
    });
    */
  }

}
