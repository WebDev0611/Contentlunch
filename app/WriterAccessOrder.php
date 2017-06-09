<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WriterAccessOrder extends Model {

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

    public function __construct (Request $request)
    {
        $this->request = $request;
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
            $writerAccessWriter = WriterAccessWriter::getOrCreate($this->request, $writerId);
            $this->targetWriter()->associate($writerAccessWriter);
        }

        if ($order->writer) {
            $writerId = $order->writer->id;
            $writerAccessWriter = WriterAccessWriter::getOrCreate($this->request, $writerId);
            $this->writer()->associate($writerAccessWriter);
        }

        if ($order->editor) {
            $writerId = $order->editor->id;
            $writerAccessWriter = WriterAccessWriter::getOrCreate($this->request, $writerId);
            $this->editor()->associate($writerAccessWriter);
        }
    }
}
