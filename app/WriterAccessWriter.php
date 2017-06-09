<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WriterAccessWriter extends Model
{
    protected $fillable = [
        'writer_id',
        'name',
        'location',
        'rating',
        'photo',
        'quote',
        'educationlevel',
        'summary',
        'specialties'
    ];

    public function orders()
    {
        return $this->hasMany('App\WriterAccessOrder', 'writer_id', 'writer_id');
    }
}
