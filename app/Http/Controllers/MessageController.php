<?php

namespace App\Http\Controllers;

use App\Account;
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
    protected $channel;

    public function __construct(Message $message, PusherManager $pusher)
    {
        $this->message = $message;
        $this->pusher = $pusher;
        $this->channel = $this->accountChannel();
    }

    public function auth(Request $request)
    {
        $channel = $request->input('channel_name');
        $socket = $request->input('socket_id');
        $response = response()->json('Denied', 403);

        if ($channel === $this->channel) {
            $response = $this->pusher->presence_auth($channel, $socket, Auth::id(), []);
        }

        return $response;
    }

    public function index()
    {
        $messages = $this->message->orderBy('id', 'desc')->take(5)->get();

        return response()->json([
            'data' => $messages,
            'channel' => $this->channel,
        ]);
    }

    protected function accountChannel()
    {
        $selectedAccount = Account::selectedAccount();
        $hash = sha1("pusher-{$selectedAccount->id}");

        return "presence-messages-$hash";
    }

    public function store(Request $request, User $user)
    {
        $message = $this->message
            ->from(Auth::user())
            ->to($user)
            ->body($request->input('body'))
            ->send();

        $this->pusher->trigger($this->channel, 'new-message', ['message' => $message->toArray() ]);

        return response()->json([ 'data' => $message ], 201);
    }
}
