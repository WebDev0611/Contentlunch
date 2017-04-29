<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Pusher\PusherManager;

class MessageController extends Controller
{
    protected $message;
    protected $pusher;

    public function __construct(Message $message, PusherManager $pusher)
    {
        $this->message = $message;
        $this->pusher = $pusher;
    }

    public function index()
    {
        $messages = $this->message->orderBy('id', 'desc')->take(5)->get();

        return response()->json(['data' => $messages]);
    }

    public function store(Request $request, User $user)
    {
        $message = $this->message
            ->from(Auth::user())
            ->to($user)
            ->body($request->input('body'))
            ->send();

        $this->pusher->trigger('messages', 'new-message', ['message' => $message->toArray() ]);

        return response()->json([ 'data' => $message ], 201);
    }
}
