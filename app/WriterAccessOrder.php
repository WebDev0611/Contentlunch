<?php

namespace App;

use App\Http\Controllers\WriterAccessController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WriterAccessOrder extends Model {

    private $writerAccess;

    private $request;

    protected $fillable = [
        'status',
        'approved',
        'autoapproved',
        'approvedwords',
        'title',
        'instructions',
        'special',
        'required',
        'optional',
        'seo',
        'allowhtml',
        'complexity',
        'writertype',
        'minwords',
        'maxwords',
        'paidreview',
        'hourstoexpire',
        'hourstocomplete',
        'hourstoapprove',
        'hourstorevise',
        'maxcost'
    ];

    public function __construct ()
    {
        $this->request = request();
        $this->writerAccess = new WriterAccessController($this->request);
    }

    public function targetWriter ()
    {
        return $this->belongsTo('App\WriterAccessWriter', 'targetwriter_id', 'writer_id');
    }

    public function writer ()
    {
        return $this->belongsTo('App\WriterAccessWriter', 'writer_id', 'writer_id');
    }

    public function editor ()
    {
        return $this->belongsTo('App\WriterAccessWriter', 'editor_id', 'writer_id');
    }

    public function creteNewOrder ($orderId)
    {
        $this->writerAccess = new WriterAccessController($this->request);
        $data = json_decode(utf8_encode($this->writerAccess->getOrders($orderId)->getContent()));

        if (isset($data->fault)) {
            return redirect()->route('content_orders.index')->with([
                'flash_message'           => $data->fault,
                'flash_message_type'      => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $this->fillOrder($data->orders[0]);

        try {
            return $this->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return ['error' => true, 'message' => $e->errorInfo[2]];
        }
    }

    public function fillOrder ($order)
    {
        foreach ($this->fillable as $param) {
            $this->$param = $order->$param;
        }

        $this->order_id = $order->id;
        $this->project_id = $order->project ? $order->project->id : null;
        $this->category_id = $order->category ? $order->category->id : null;
        $this->category_name = $order->category ? $order->category->name : null;
        $this->asset_id = $order->asset ? $order->asset->id : null;
        $this->asset_name = $order->asset ? $order->asset->name : null;
        $this->expertise_id = $order->expertise ? $order->expertise->id : null;
        $this->expertise_name = $order->expertise ? $order->expertise->name : null;
        $this->lovelist = $order->recipients->lovelist;

        $this->fillWriters($order);
    }

    private function fillWriters ($order)
    {
        if ($order->recipients->writer) {
            $writerId = $order->recipients->writer->id;
            $writerAccessWriter = WriterAccessWriter::whereWriterId($writerId)->first();

            if (!$writerAccessWriter) {
                $data = json_decode(utf8_encode($this->writerAccess->getWriter($writerId)->getContent()));
                $writer = collect($data->writers[0])->toArray();
                $writer['writer_id'] = $writer['id'];

                $writerAccessWriter = WriterAccessWriter::create($writer);
            }

            $this->targetWriter()->associate($writerAccessWriter);
            $this->save();
        }

        // TODO: Add 2 more: writer, editor

    }
}
