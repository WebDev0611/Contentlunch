<?php

use \Launch\Scheduler\Scheduler;

class ConferencesController extends BaseController {
  
  public function index($accountID)
  {
    $query = Conference::with('user')->orderBy('created_at', 'desc');
    if ($accountID == 'all') {
      if ( ! $this->isGlobalAdmin()) {
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
    $minDate->setTime(0, 0, 0); // if we don't set the time, it takes the current time (we want the beginning of this day)
    $maxDate = new \DateTime('+13 days');
    $maxDate->setTime(23, 59, 59); // if we don't set the time, it takes the current time (we want the end of this day)
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
    if ($this->isGlobalAdmin()) {
      if (Input::get('scheduled_date') && Input::get('scheduled_time')) {
        $conference->scheduled_date = new \DateTime(Input::get('scheduled_date') .' '. Input::get('scheduled_time'));
      }
      $conference->status = 1;
    }

    if ($conference->save()) {
      Scheduler::videoConferenceReminder($conference);
      $this->globalAdminEmail($conference->id);
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
    if ($this->isGlobalAdmin()) {
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

    public function globalAdminEmail($conference_id) {
        $emails = DB::table('roles')
            ->join('assigned_roles', 'roles.id', '=', 'assigned_roles.role_id')
            ->join('users', 'users.id', '=', 'assigned_roles.user_id')
            ->where('roles.name', 'global_admin')
            ->lists('email');

        $conference = Conference::with('user', 'account')->find($conference_id);

        Mail::send('emails.conference.request', compact('conference'), function($message) use ($emails) {
            $message->to($emails)->subject('Consult Video Conference Request');
        });
    }

}