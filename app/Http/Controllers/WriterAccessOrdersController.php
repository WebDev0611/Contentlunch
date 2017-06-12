<?php

namespace App\Http\Controllers;

use App\Traits\CreatesNewWriterAccessOrder;
use App\User;
use App\WriterAccessOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class WriterAccessOrdersController extends Controller {

    use CreatesNewWriterAccessOrder;

    private $WAController;

    private $users;

    private $user;

    public function __construct (Request $request)
    {
        $this->WAController = new WriterAccessController($request);
        $this->users = User::where('writer_access_Project_id', '!=', '')->whereNotNull('writer_access_Project_id')->get();
        $this->user = $request->user();
    }

    public function fetch ()
    {
        $apiOrders = $this->getUsersOrdersFromAPI();

        $apiOrders->each(function ($apiOrder) {
            $localOrder = WriterAccessOrder::whereOrderId($apiOrder->id)->first();
            if (!$localOrder) {
                // order somehow doesn't exist in our DB so let's create it
                $this->createWriterAccessOrder($apiOrder->id);
            } elseif ($apiOrder->status !== $localOrder->status && $localOrder->status !== 'Deleted') {
                // order status has changed so let's update it
                $fullApiOrder = json_decode(utf8_encode($this->WAController->getOrders($apiOrder->id)->getContent()));
                $localOrder->fillOrder($fullApiOrder->orders[0]);
                $localOrder->save();

                $this->sendEmailStatusNotification([
                    'oldStatus' => $localOrder->status,
                    'newStatus' => $apiOrder->status,
                    'orderId' => $localOrder->id,
                    'userEmail' => $localOrder->user->email
                ]);
            }
        });
    }

    public function getOrders ()
    {
        if (!$this->user) {
            return response()->json('You don\'t have permission to access this resource.');
        }

        return $this->user->writerAccessOrders()->with('writer')->orderBy('updated_at', 'desc')->get();
    }

    public function getOrdersCount (Request $request)
    {

        $orders = $this->user->writerAccessOrders();
        $count = $orders->count();

        if ($request->has('pending-approval')) {
            $count = $orders->whereStatus('Pending Approval')->count();
        }

        return response()->json(['count' => $count]);
    }

    private function getUsersOrdersFromAPI ()
    {
        $allOrders = json_decode(utf8_encode($this->WAController->getOrders()->getContent()))->orders;

        // Get only orders that belong to one of our users' projects
        $orders = [];
        foreach ($allOrders as $order) {
            $user = $this->users->where('writer_access_Project_id', (string)$order->project->id)->first();
            if ($user) {
                $orders[] = $order;
            }
        }

        return collect($orders);
    }

    private function sendEmailStatusNotification ($emailData)
    {
        Mail::send('emails.writeraccess_order_status_update', $emailData, function($message) use ($emailData){
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to($emailData['userEmail'])
                ->subject('Content Order Status Change');
        });
    }
}
