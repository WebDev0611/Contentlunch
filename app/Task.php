<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $fillable = [
        'name',
        'explanation',
        'start_date',
        'due_date',
        'user_id',
        'account_id',
        'status',
    ];

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany('App\User');
    }

    public static function search($term, $account = null)
    {
        if (!$account) {
            $account = Account::selectedAccount();
        }

        return $account
            ->tasks()
            ->where(function($q) use ($term) {
                $q->orWhere('name', 'like', '%' . $term . '%')
                  ->orWhere('explanation', 'like', '%' . $term . '%');
            })
            ->get();
    }

    public function isAssignedTo(User $user)
    {
        return (boolean) $this->assignedUsers()
            ->where('users.id', $user->id)
            ->count();
    }
}
