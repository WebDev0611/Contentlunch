<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id', 'recipient_id', 'body'];

    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo('App\User', 'recipient_id');
    }

    protected function fluent(array $data)
    {
        return (new Message(array_merge($this->toArray(), $data)));
    }

    public function to(User $user)
    {
        return $this->fluent(['recipient_id' => $user->id]);
    }

    public function from(User $user)
    {
        return $this->fluent(['sender_id' => $user->id]);
    }

    public function body($body)
    {
        return $this->fluent(['body' => $body]);
    }

    public function send()
    {
        return self::create($this->toArray());
    }
}
