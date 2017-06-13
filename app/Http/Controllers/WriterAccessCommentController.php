<?php

namespace App\Http\Controllers;

use App\Jobs\SendWriterAccessCommentNotification;
use App\User;
use App\WriterAccessComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WriterAccessCommentController extends Controller {

    protected $WAController;

    protected $order;

    protected $users;

    protected $wa_comments;


    public function __construct (Request $request)
    {
        $this->WAController = new WriterAccessController($request);
        $this->users = User::where('writer_access_Project_id', '!=', '')->whereNotNull('writer_access_Project_id')->get();
        $this->wa_comments = WriterAccessComment::all();
    }

    public function fetch ()
    {
        // TODO Change
        $orders = $this->getUsersOrders();

        foreach ($orders as $order) {
            $this->order = $order;
            $this->fetchNewComments($order['order']->id);
        }

        $this->sendNotificationEmails();

        return response()->json('done');
    }

    public function sendNotificationEmails ()
    {
        $comments = WriterAccessComment::userNotNotified()->notFromClient()->get();
        $comments->each(function ($comment) {
            $this->dispatch((new SendWriterAccessCommentNotification($comment))->delay(10));
        });
    }

    public function getOrderComments ($orderId)
    {
        $order = Auth::user()->writerAccessOrders()->whereOrderId($orderId)->first();

        if (!$order) {
            return response()->json(['data' => 'You don\'t have sufficient permissions to do this.'], 403);
        }

        return $order->comments()
            ->with('user')
            ->with('writer')
            ->with('editor')
            ->orderBy('timestamp', 'desc')->get();
    }

    public function postOrderComment (Request $request, $orderId)
    {
        $response = $this->WAController->postComment($request, $orderId);
        $content = json_decode($response->getContent());

        if (isset($content->error) || isset($content->fault)) {
            return response()->json($content, 500);
        }

        $this->order = $this->getUsersOrders([$orderId])[0]; // TODO Change
        $this->fetchNewComments($orderId);

        return response()->json(['message' => 'Comment successfully posted.']);
    }

    private function fetchNewComments ($orderId)
    {
        // Get comments for order and save them to DB (if they're not already saved)
        $apiResponse = $this->WAController->comments($orderId)->getContent();
        $orderComments = collect(json_decode(utf8_encode($apiResponse))->orders)->pluck('comments')->flatten();

        $orderComments->each(function ($comment) {
            if (!$this->commentExists($comment)) {
                $this->createNewComment($comment);
            }
        });
    }

    private function createNewComment ($comment)
    {
        $wa_comment = new WriterAccessComment();

        $wa_comment->user_id = $this->order['user_id'];
        $wa_comment->order_id = $this->order['order']->id;
        $wa_comment->order_title = $this->order['order']->title;
        $wa_comment->timestamp = $comment->timestamp;

        if (property_exists($comment, 'writer')) {
            $wa_comment->writer_id = $comment->writer->id;
            $wa_comment->writer_name = $comment->writer->name;
            $wa_comment->note = $comment->writer->note;
        }

        if (property_exists($comment, 'editor')) {
            $wa_comment->editor_id = $comment->editor->id;
            $wa_comment->editor_name = $comment->editor->name;
            $wa_comment->note = $comment->editor->note;
        }

        if (property_exists($comment, 'client')) {
            $wa_comment->from_client = true;
            $wa_comment->note = $comment->client->note;
        }

        return $wa_comment->save();
    }

    private function commentExists ($comment)
    {
        return !$this->wa_comments->where('order_id', (string)$this->order['order']->id)
            ->where('timestamp', $comment->timestamp)->isEmpty();
    }
}
