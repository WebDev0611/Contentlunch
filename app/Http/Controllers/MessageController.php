<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use App\Message;
use App\Services\AccountService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Pusher\PusherManager;

class MessageController extends Controller
{
    protected $message;
    protected $pusher;
    protected $channel;
    protected $selectedAccount;
    protected $accountService;

    public function __construct(Message $message,
                                PusherManager $pusher,
                                AccountService $accountService)
    {
        $this->message = $message;
        $this->pusher = $pusher;
        $this->channel = $this->accountChannel();
        $this->selectedAccount = Account::selectedAccount();
        $this->accountService = $accountService;
    }

    public function auth(Request $request)
    {
        $channel = $request->input('channel_name');
        $socket = $request->input('socket_id');
        $response = response()->json('Denied', 403);

        if ($channel === $this->channel) {
            $response = $this->pusher->presence_auth($channel, $socket, Auth::id(), Auth::user()->toArray());
        }

        return $response;
    }

    public function index()
    {
        return response()->json([
            'data' => $this->getMessages(),
            'channel' => $this->channel,
        ]);
    }

    protected function getMessages()
    {
        return $this->accountService
            ->collaborators()
            ->map(function($user) {
                $user->messages = Auth::user()->conversationWith($user);

                return $user;
            });
    }

    protected function accountChannel()
    {
        $selectedAccount = Account::selectedAccount()->proxyToParent();
        $hash = sha1("pusher-{$selectedAccount->id}");

        return "presence-messages-$hash";
    }

    public function store(Request $request, User $user)
    {
        $message = $this->createMessage($user, $request->input('body'));

        $this->triggerPusherEvent($message);

        return response()->json([ 'data' => $message ], 201);
    }

    protected function createMessage(User $user, $body)
    {
        $message = $this->message
            ->from(Auth::user())
            ->to($user)
            ->body($body)
            ->send();

        $message->senderData = $message->present()->sender;
        $message = $message->toArray();

        unset($message['sender']);

        return $message;
    }

    protected function triggerPusherEvent(array $message)
    {
        return $this->pusher->trigger($this->channel, 'new-message', [ 'message' => $message ]);
    }
}
