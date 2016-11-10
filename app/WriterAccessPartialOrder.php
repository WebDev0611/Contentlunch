<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\WriterAccessPrice;

class WriterAccessPartialOrder extends Model
{
    public $table = 'writer_access_partial_orders';

    protected $fillable = [
        'project_name',
        'duedate',
        'asset_type_id',
        'wordcount',
        'writer_level',
        'content_title',
        'instructions',
        'narrative_voice',
        'target_audience',
        'tone_of_writing',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function assetType()
    {
        return $this->belongsTo('App\WriterAccessAssetType', 'asset_type_id', 'writer_access_id');
    }

    public function getPriceAttribute()
    {
        return WriterAccessPrice::where('asset_type_id', $this->asset_type_id)
            ->where('writer_level', $this->writer_level)
            ->where('wordcount', $this->wordcount)
            ->first()
            ->fee;
    }
}
