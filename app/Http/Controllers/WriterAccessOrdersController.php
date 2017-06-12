<?php

namespace App\Http\Controllers;

use App\Traits\CreatesNewWriterAccessOrder;
use App\User;
use App\WriterAccessOrder;
use function Clue\StreamFilter\fun;
use Illuminate\Http\Request;


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
            } elseif ($apiOrder->status !== $localOrder->status) {
                // TODO: order status has changed so let's update it
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
}
