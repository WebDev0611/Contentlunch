<?php

namespace App\Http\Controllers;

use App\Account;
use App\Traits\CreatesNewWriterAccessOrder;
use App\User;
use App\WriterAccessOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public function show (Request $request, $id)
    {
        $order = WriterAccessOrder::whereOrderId($id)->first();

        if (!$order || Auth::user()->cant('show', $order)) {
            return redirect()->route('content_orders.index')->with([
                'flash_message'           => 'You don\'t have permission to access this order.',
                'flash_message_type'      => 'danger',
                'flash_message_important' => true,
            ]);
        }
        elseif ($order->status != 'Pending Approval' && $order->status != 'Approved') {
            return redirect()->route('content_orders.index')->with([
                'flash_message'           => 'This order is not yet available for preview.',
                'flash_message_type'      => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $connections = Account::selectedAccount()
            ->connections()
            ->where('active', 1)
            ->get();

        return view('content.order', compact('order', 'connections'));
    }

    public function fetch ()
    {
        $apiOrders = $this->getUsersOrdersFromAPI();

        $apiOrders->each(function ($apiOrder) {
            $localOrder = WriterAccessOrder::whereOrderId($apiOrder->id)->first();
            if (!$localOrder) {
                // order somehow doesn't exist in our DB so let's create it
                $this->createWriterAccessOrder($apiOrder->id);
            }
            elseif ($apiOrder->status !== $localOrder->status && $localOrder->status !== 'Deleted') {
                // order status has changed so let's update it
                $fullApiOrder = $this->getFullApiOrder($apiOrder);

                $this->sendEmailStatusNotification([
                    'oldStatus' => $localOrder->status,
                    'newStatus' => $apiOrder->status,
                    'orderId'   => $localOrder->order_id,
                    'userEmail' => $localOrder->user->email
                ]);

                $localOrder->fillOrder($fullApiOrder);
                $localOrder->save();
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

    private function getFullApiOrder ($apiOrder)
    {
        if ($apiOrder->status == 'Pending Approval' || $apiOrder->status == 'Approved') {
            $data = json_decode(utf8_encode($this->WAController->getOrders($apiOrder->id, true)->getContent()));
            if (isset($data->preview)) {
                $data->order->preview = $data->preview;
            }

            return $data->order;
        }
        else {
            $data = json_decode(utf8_encode($this->WAController->getOrders($apiOrder->id)->getContent()));

            return $data->orders[0];
        }
    }

    private function sendEmailStatusNotification ($emailData)
    {
        Mail::send('emails.writeraccess_order_status_update', $emailData, function ($message) use ($emailData) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to($emailData['userEmail'])
                ->subject('Content Order Status Change');
        });
    }
}
