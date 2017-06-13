<?php

namespace App\Traits;


use App\Http\Controllers\WriterAccessController;
use App\WriterAccessOrder;
use Illuminate\Support\Facades\Log;

trait CreatesNewWriterAccessOrder {

    public function createWriterAccessOrder ($orderId, $full = false)
    {
        $order = new WriterAccessOrder();

        $apiOrder = $this->getApiOrder($orderId, $full);

        if(is_array($apiOrder) && isset($apiOrder['error'])) {
            return $apiOrder;
        }

        $order->fillOrder($apiOrder);

        try {
            return $order->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => true, 'message' => $e->errorInfo[2]];
        }
    }

    public function getApiOrder ($orderId, $full = false)
    {
        $writerAccess = new WriterAccessController(request());
        $data = json_decode(utf8_encode($writerAccess->getOrders($orderId, $full)->getContent()));

        if (isset($data->fault)) {
            $message = 'Error when trying to get Writer Access order . ' . $orderId.' . Fault: ' . $data->fault;
            Log::error($message);
            return ['error' => true, 'message' => $message];
        }

        if (isset($data->preview)) {
            $data->order->preview = $data->preview;
        }

        return !$full ? $data->orders[0] : $data->order;
    }
}