<?php

namespace App;

use App\Http\Controllers\WriterAccessController;
use Illuminate\Database\Eloquent\Model;

class WriterAccessOrder extends Model {
    

    public static function creteNewOrder ($orderId)
    {
        $writerAccess = new WriterAccessController(request());
        $data = json_decode(utf8_encode($writerAccess->getOrders($orderId)->getContent()));

        if (isset($data->fault)) {
            return redirect()->route('content_orders.index')->with([
                'flash_message' => $data->fault,
                'flash_message_type' => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $order = new self();
        $order->fillOrder($data->orders[0]);

        try {
            return $order->save();
        } catch ( \Illuminate\Database\QueryException $e) {
            return ['error' => true, 'message' => $e->errorInfo[2]];
        }
    }

    public function fillOrder ($order)
    {
        $this->order_id = $order->id;
        $this->status = $order->status;
        $this->approved = $order->approved;
        $this->autoapproved = $order->autoapproved;
        $this->approvedwords = $order->approvedwords;
        $this->project_id = $order->project ? $order->project->id : null;
        $this->category_id = $order->category ? $order->category->id : null;
        $this->category_name = $order->category ? $order->category->name : null;
        $this->asset_id = $order->asset ? $order->asset->id : null;
        $this->asset_name = $order->asset ? $order->asset->name : null;
        $this->expertise_id = $order->expertise ? $order->expertise->id : null;
        $this->expertise_name = $order->expertise ? $order->expertise->name : null;
        $this->title = $order->title;
        $this->instructions = $order->instructions;
        $this->special = $order->special;
        $this->required = $order->required;
        $this->optional = $order->optional;
        $this->seo = $order->seo;
        $this->allowhtml = $order->allowhtml;
        $this->complexity = $order->complexity;
        $this->writertype = $order->writertype;
        $this->minwords = $order->minwords;
        $this->maxwords = $order->maxwords;
        $this->paidreview = $order->paidreview;
        $this->hourstoexpire = $order->hourstoexpire;
        $this->hourstocomplete = $order->hourstocomplete;
        $this->hourstoapprove = $order->hourstoapprove;
        $this->hourstorevise = $order->hourstorevise;
        $this->maxcost = $order->maxcost;
        $this->lovelist = $order->recipients->lovelist;

        //Add 3 more: targetwriter, writer, editor

    }
}
