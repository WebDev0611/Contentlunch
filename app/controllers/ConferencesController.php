<?php

use \Launch\Scheduler\Scheduler;

class ConferencesController extends BaseController {
  
  public function index($accountID)
  {
    $query = Conference::with('user')->orderBy('created_at', 'desc');
    if ($accountID == 'all') {
      if ( ! $this->hasRole('global_admin')) {
        return $this->responseAccessDenied();
      }
    } else {
      if ( ! $this->inAccount($accountID)) {
        return $this->responseAccessDenied();
      }
      $query->where('account_id', $accountID);
    }
    return $query->get();
  }

  public function store($accountID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $user = Confide::user();
    $conference = new Conference;
    $conference->status = 0;
    $conference->account_id = $accountID;
    $conference->user_id = $user->id;
    // Schedule date requests must > 3 days and < 2 weeks
    $minDate = new \DateTime('+4 days');
    $maxDate = new \DateTime('+13 days');
    if (Input::get('date_1') && Input::get('time_1')) {
      $date = new \DateTime(Input::get('date_1') .' '. Input::get('time_1'));
      if ($date < $minDate || $date > $maxDate) {
        return $this->responseError("Invalid date: ". $date->format('m/d/Y H:i:s') ." Please select a date between ". $minDate->format('m/d/Y') .' and '. $maxDate->format('m/d/Y'));
      }
      $conference->date_1 = $date;
    }
    if (Input::get('date_2') && Input::get('time_2')) {
      $date = new \DateTime(Input::get('date_2') .' '. Input::get('time_2'));
      if ($date < $minDate || $date > $maxDate) {
        return $this->responseError("Invalid date: ". $date->format('m/d/Y H:i:s') ." Please select a date between ". $minDate->format('m/d/Y') .' and '. $maxDate->format('m/d/Y'));
      }
      $conference->date_2 = $date;
    }
    if ($this->hasRole('global_admin')) {
      if (Input::get('scheduled_date') && Input::get('scheduled_time')) {
        $conference->scheduled_date = new \DateTime(Input::get('scheduled_date') .' '. Input::get('scheduled_time'));
      }
      $conference->status = 1;
    }

    if ($conference->save()) {
      Scheduler::videoConferenceReminder($conference);
      return $this->show($accountID, $conference->id);
    }
    return $this->responseError($conference->errors()->all(':message'));
  }

  public function show($accountID, $conferenceID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $conference = Conference::find($conferenceID);
    return $conference;
  }

  public function update($accountID, $conferenceID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $conference = Conference::find($conferenceID);
    if (Input::get('date_1') && Input::get('time_1')) {
      $conference->date_1 = new \DateTime(Input::get('date_1') .' '. Input::get('time_1'));
    }
    if (Input::get('date_2') && Input::get('time_2')) {
      $conference->date_2 = new \DateTime(Input::get('date_2') .' '. Input::get('time_2'));
    }
    // Check if global admin is editing the conference with a scheduled date
    if ($this->hasRole('global_admin')) {
      if (Input::get('scheduled_date') && Input::get('scheduled_time')) {
        $conference->scheduled_date = new \DateTime(Input::get('scheduled_date') .' '. Input::get('scheduled_time'));
        // Now that there is a scheduled date, bump the status to scheduled
        $conference->status = 1;
      }
    }

    if ($conference->updateUniques()) {
      Scheduler::videoConferenceReminder($conference);
      return $this->show($accountID, $conferenceID);
    }
    return $this->responseError($conference->errors()->all(':message'));
  }

  public function destroy($accountID, $conferenceID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $conference = Conference::find($conferenceID);
    if ($conference->delete()) {
      return ['success' => 'OK'];
    }
    return $this->responseError("Couldn't delete conference");
  }

  public function test()
  {
    Scheduler::videoConferenceReminder(Conference::find(1));
  }

  // Scheduler Jobs
  // -------------------------
  public function emailReminder($job, $data = [])
  {
    Log::info('Running emailReminder job.');

    Mail::send('emails.conference.reminder', $data, function ($message) {
      $message->to('cameronspear@gmail.com')->subject('Content Launch Conference Reminder');
    });

    $job->delete();
  }

}