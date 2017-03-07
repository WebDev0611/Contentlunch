<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Calendar extends Model
{
    protected $table = 'calendar';


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function contentTypes()
    {
        return $this->belongsToMany('App\ContentType');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function store($user, $account_id, $input)
    {
        $this->name = $input['name'];
        $this->account_id = $account_id;

        $this->color = (!empty($input['color'])) ? $input['color'] : null;
        $this->show_ideas = (!empty($input['show_ideas']) && $input['show_ideas'] == '1') ? true : false;
        $this->show_tasks = (!empty($input['show_tasks']) && $input['show_tasks'] == '1') ? true : false;

        $newCalendar = $user->calendars()->save($this);
        if (!empty($input['content_type_ids'])) {
            $newCalendar->contentTypes()->sync($input['content_type_ids']);
            $newCalendar->save();
        }

        return $newCalendar;
    }

    // Creates default calendar for the user
    public static function createDefaultCalendar(Request $request)
    {
        $input['name'] = 'Personal Calendar';
        $input['color'] = 'd68ae6';
        $input['show_ideas'] = true;
        $input['show_tasks'] = true;
        $input['content_type_ids'] = ContentType::where('active', '!=', 0)->pluck('id')->toArray();

        $cal = new Calendar();

        return $cal->store($request->user(), Account::selectedAccount()->id, $input);
    }
}
