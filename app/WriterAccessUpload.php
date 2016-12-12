<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WriterAccessUpload extends Model
{
    public $table = 'writer_access_uploads';

    public $fillable = [
        'writer_access_partial_order_id',
        'file_path',
    ];

    public function order()
    {
        return $this->belongsTo('App\WriterAccessPartialOrder', 'writer_access_partial_order_id');
    }
}
