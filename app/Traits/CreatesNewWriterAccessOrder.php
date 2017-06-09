<?php

namespace App\Traits;


use App\Http\Controllers\WriterAccessController;
use App\WriterAccessOrder;

trait CreatesNewWriterAccessOrder {

    public function createWriterAccessOrder ($orderId)
    {
        $request = request();
        $writerAccess = new WriterAccessController($request);
        $order = new WriterAccessOrder($request);

        $data = json_decode(utf8_encode($writerAccess->getOrders($orderId)->getContent()));

        if (isset($data->fault)) {
            return redirect()->route('content_orders.index')->with([
                'flash_message'           => $data->fault,
                'flash_message_type'      => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $order->fillOrder($data->orders[0]);

        try {
            return $order->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => true, 'message' => $e->errorInfo[2]];
        }
    }
}