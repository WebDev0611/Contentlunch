<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class WriterAccessOrdersController extends Controller
{
    private $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user();
    }

    public function getOrders()
    {
        if(!$this->user) {
            return response()->json('You don\'t have permission to access this resource.');
        }

        return $this->user->writerAccessOrders()->with('writer')->orderBy('updated_at', 'desc')->get();
    }

    public function getOrdersCount(Request $request) {

        $orders = $this->user->writerAccessOrders();
        $count = $orders->count();

        if($request->has('pending-approval')) {
            $count = $orders->whereStatus('Pending Approval')->count();
        }

        return response()->json(['count' => $count]);
    }
}
