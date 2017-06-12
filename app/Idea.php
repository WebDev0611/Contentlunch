<?php

namespace App;

use App\Account;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    use Orderable;

    protected $table = 'idea';
    public $fillable = [
        'user_id',
        'account_id',
        'name',
        'text',
        'tags',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function collaborators()
    {
        return $this->belongsToMany('App\User');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function calendar()
    {
        return $this->belongsTo('App\Calendar');
    }

    public function contents()
    {
        return $this->belongsToMany('App\Content');
    }

    public function getCreatedAtDiffAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getUpdatedAtDiffAttribute()
    {
        return $this->updated_at->diffForHumans();
    }

    public function scopeMonthly($query)
    {
        return $query->whereBetween('idea.created_at', [
            \Carbon\Carbon::now()->subMonth(),
            \Carbon\Carbon::now(),
        ]);
    }

    public function park()
    {
        $this->update([ 'status' => 'parked' ]);
    }

    public function activate()
    {
        $this->update([ 'status' => 'active' ]);
    }

    public function reject()
    {
        $this->update([ 'status' => 'rejected' ]);
    }

    public static function search($term, $account = null)
    {
        if (!$account) {
            $account = Account::selectedAccount();
        }

        return $account
            ->ideas()
            ->where(function($q) use ($term) {
                $q->orWhere('name', 'like', '%' . $term . '%')
                  ->orWhere('text', 'like', '%' . $term . '%');
            })
            ->get();
    }

    public function hasCollaborator(User $user)
    {
        $isAuthor = $this->user_id == $user->id;
        $isCollaborator = (boolean) $this->collaborators()
            ->where('users.id', $user->id)
            ->count();

        if ($isAuthor && !$isCollaborator) {
            $this->collaborators()->attach($user);
            $isCollaborator = true;
        }

        return $isCollaborator;
    }

    public static function accountIdeas(Account $account)
    {
        $ideas = $account
            ->ideas()
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get()
            ->map(function ($idea) {
                $idea->created_diff = $idea->createdAtDiff;
                $idea->updated_diff = $idea->updatedAtDiff;

                return $idea;
            });

        return $ideas;
    }

    public static function calendarIdeas(Calendar $calendar)
    {
        $ideas = $calendar
            ->ideas()
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get()
            ->map(function ($idea) {
                $idea->created_diff = $idea->createdAtDiff;
                $idea->updated_diff = $idea->updatedAtDiff;

                return $idea;
            });

        return $ideas;
    }
}
