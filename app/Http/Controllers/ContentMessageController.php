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
        return $content->messages()->get()->map(function($message) {
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

        if ($channel === $this->contentChannel($content)) {
            $response = $this->pusher->presence_auth($channel, $socket, Auth::id(), Auth::user()->toArray());
        }

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
