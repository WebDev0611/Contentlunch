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
        'approvedrating',
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
    }

    public function user ()
    {
        return $this->belongsTo('App\User', 'project_id', 'writer_access_Project_id');
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

    public function comments ()
    {
        return $this->hasMany('App\WriterAccessComment', 'order_id', 'order_id');
    }

    public function scopeNotDeleted ($query)
    {
        return $query->where('status', '!=', 'Deleted');
    }

    public function fillOrder ($order)
    {
        foreach ($this->fillable as $param) {
            $this->$param = isset($order->$param) ? $order->$param : null;
        }

        $this->order_id = $order->id;
        $this->project_id = isset($order->project) ? $order->project->id : null;
        $this->category_id = isset($order->category) ? $order->category->id : null;
        $this->category_name = isset($order->category) ? $order->category->name : null;
        $this->asset_id = isset($order->asset) ? $order->asset->id : null;
        $this->asset_name = isset($order->asset) ? $order->asset->name : null;
        $this->expertise_id = isset($order->expertise) ? $order->expertise->id : null;
        $this->expertise_name = isset($order->expertise) ? $order->expertise->name : null;
        $this->lovelist = $order->recipients->lovelist;
        $this->preview_title = isset($order->preview) ? $order->preview->title : null;
        $this->preview_text = isset($order->preview) ? $order->preview->text : null;

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
