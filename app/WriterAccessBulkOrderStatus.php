<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WriterAccessBulkOrderStatus extends Model
{

    protected $table = 'writer_access_bulk_order_statuses';

    protected $fillable = [
        'status_percentage',
        'total_orders',
        'completed_orders',
        'completed'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
