<?php

namespace App\Http\Controllers;

use App\User;
use App\WriterAccessComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


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

    public function fetchComments ()
    {
        $orders = $this->getAllUsersOrders();

        foreach ($orders as $order) {
            $this->order = $order;

            // Get comments for order and save them to DB
            $apiResponse = $this->WAController->comments($order['order']->id)->getContent();
            $orderComments = collect(json_decode(utf8_encode($apiResponse))->orders)->pluck('comments')->flatten();

            $orderComments->each(function ($comment) {
                if(!$this->commentExists($comment)) {
                    $this->createNewComment($comment);
                }
            });
        }

        return response()->json('ok');
    }

    private function getAllUsersOrders ()
    {
        $allOrders = collect(json_decode(utf8_encode($this->WAController->allOrders()->getContent()))->orders);

        // Get only orders that belong to one of our users' projects
        $orders = [];
        foreach ($allOrders as $singleOrder) {
            $user = $this->users->where('writer_access_Project_id', (string)$singleOrder->project->id)->first();
            if ($user) {
                $orders[] = [
                    'user_id' => $user->id,
                    'order'   => $singleOrder
                ];
            }
        }

        return $orders;
    }

    private function createNewComment ($comment)
    {
        $wa_comment = new WriterAccessComment();

        $wa_comment->user_id = $this->order['user_id'];
        $wa_comment->order_id = $this->order['order']->id;
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
