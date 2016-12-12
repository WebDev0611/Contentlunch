<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;

class Idea extends Model
{
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

    public function account()
    {
        return $this->belongsTo('App\Account');
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
}