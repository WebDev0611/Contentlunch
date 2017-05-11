<?php

namespace App\Http\Controllers;

use App\User;
use App\WriterAccessComment;
use Illuminate\Http\Request;


class WriterAccessCommentController extends Controller {

    protected $WAController;

    protected $order;

    protected $users;


    public function __construct (Request $request)
    {
        $this->WAController = new WriterAccessController($request);
        $this->users = User::where('writer_access_Project_id', '!=', '')->whereNotNull('writer_access_Project_id')->get();
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
                $this->createNewComment($comment);
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

        if ($comment->writer) {
            $wa_comment->writer_id = $comment->writer->id;
            $wa_comment->writer_name = $comment->writer->name;
            $wa_comment->note = $comment->writer->note;
        }

        return $wa_comment->save();
    }

}
