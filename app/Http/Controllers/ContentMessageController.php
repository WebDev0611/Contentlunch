<?php

namespace App\Http\Controllers;

use App\Content;
use App\ContentMessage;
use App\Http\Requests;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Vinkla\Pusher\PusherManager;

class ContentMessageController extends Controller
{
    protected $message;
    protected $pusher;
    protected $channel;
    protected $selectedAccount;
    protected $accountService;

    public function __construct(ContentMessage $message, PusherManager $pusher)
    {
        $this->message = $message;
        $this->pusher = $pusher;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Content $content)
    {
        return response()->json([
            'data' => $this->contentMessages($content),
            'channel' => $this->contentChannel($content),
        ]);
    }

    public function contentMessages(Content $content)
    {
        return $content->messages()->orderBy('created_at', 'desc')->get()->map(function($message) {
            $message->senderData = $message->present()->sender;

            return $message;
        });
    }

    protected function contentChannel($content)
    {
        $hash = sha1("pusher-content-{$content->id}");

        return "presence-content-$hash";
    }

    /**
     * Authenticates the user via Pusher.
     *
     * @return \Illuminate\Http\Response
     */
    public function auth(Request $request, Content $content)
    {
        $channel = $request->input('channel_name');
        $socket = $request->input('socket_id');
        $response = response()->json('Denied', 403);

        if ($channel === $this->contentChannel($content) && $this->userIsAllowedAccess($content)) {
            $response = $this->pusher->presence_auth($channel, $socket, Auth::id(), Auth::user()->toArray());
        }

        return $response;
    }

    protected function userIsAllowedAccess($content)
    {
        return $content->hasCollaborator(Auth::user()) || $content->hasGuest(Auth::user());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Content $content)
    {
        if (!$this->userIsAllowedAccess($content)) {
            return response()->json([ 'data' => 'Forbidden' ], 403);
        }

        $message = $this->createMessage($content, $request->input('body'));

        $this->triggerPusherEvent($content, $message);

        return response()->json([ 'data' => $message ], 201);
    }

    protected function createMessage(Content $content, $message)
    {
        $message = $content->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $message,
        ]);

        $message->senderData = $message->present()->sender;
        $message = $message->toArray();

        unset($message['sender']);

        return $message;
    }

    protected function triggerPusherEvent(Content $content, array $message)
    {
        $channel = $this->contentChannel($content);

        return $this->pusher->trigger($channel, 'new-message', [ 'message' => $message ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
