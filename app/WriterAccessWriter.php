<?php

namespace App;

use App\Http\Controllers\WriterAccessController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    /**
     * Get writer from our DB, or if it doesn't exist, create it by fetching writer data from WriterAccess API
     * @param Request $request
     * @param int        $writerId
     * @return WriterAccessWriter
     */
    public static function getOrCreate (Request $request, $writerId)
    {
        $writerAccessWriter = self::whereWriterId($writerId)->first();

        if (!$writerAccessWriter) {
            $writerAccess = new WriterAccessController($request);

            $data = json_decode(utf8_encode($writerAccess->getWriter($writerId)->getContent()));
            $writer = collect($data->writers[0])->toArray();
            $writer['writer_id'] = $writer['id'];

            $writerAccessWriter = self::create($writer);
        }

        return $writerAccessWriter;
    }
}
