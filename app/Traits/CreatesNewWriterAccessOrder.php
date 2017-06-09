<?php

namespace App\Traits;


use App\Http\Controllers\WriterAccessController;
use App\WriterAccessOrder;

trait CreatesNewWriterAccessOrder {

    private $writerAccess;

    private $request;

    private $order;

    public function createWriterAccessOrder ($orderId)
    {
        $this->request = request();
        $this->writerAccess = new WriterAccessController($this->request);
        $this->order = new WriterAccessOrder($this->request);

        $data = json_decode(utf8_encode($this->writerAccess->getOrders($orderId)->getContent()));

        if (isset($data->fault)) {
            return redirect()->route('content_orders.index')->with([
                'flash_message'           => $data->fault,
                'flash_message_type'      => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $this->order->fillOrder($data->orders[0]);

        try {
            return $this->order->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => true, 'message' => $e->errorInfo[2]];
        }
    }
}