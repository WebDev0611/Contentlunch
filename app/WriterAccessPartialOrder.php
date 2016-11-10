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

    public function writerAccessFormat()
    {
        return [
            'assetType' => $this->asset_type_id,
            'minwords' => $this->minwords,
            'maxwords' => $this->maxwords,
            'writer' => $this->writer_level,
            'hourstocomplete' => $this->hours,
            'title' => $this->content_title,
            'instructions' => $this->createInstructions()
        ];
    }

    public function getMinwordsAttribute()
    {
        return (int) ($this->wordcount - ($this->wordcount * .1));
    }

    public function getMaxwordsAttribute()
    {
        return (int) ($this->wordcount + ($this->wordcount * .1));
    }

    public function getHoursAttribute()
    {
        $today = new \Carbon\Carbon(date('Y-m-d H:i:s'));
        $duedate = new \Carbon\Carbon(date($this->duedate));

        $diff = $duedate->diff($today);

        $hours = $diff->h;
        $hours = $hours + ($diff->days * 24);

        // NOTE: WriterAccess expects to see 4, 12, or increments of 24 hours.
        // We are only going to worry about full days or a half day if submitted
        // for next day duedates.

        //round down to the nearest 24 hours
        $hours = $hours - $hours % 24;

        //Set $hours to 12 if rounding down == 0
        $hours = $hours == 0 ? 12 : $hours;

        return $hours;
    }

    private function createInstructions()
    {
        return "$this->instructions \n" .
            "\nTarget Audience: \n$this->target_audience\n" .
            "\nTone of Writing: \n$this->tone_of_writing\n" .
            "\nNarrative Voice: \n$this->narrative_voice\n";
    }
}
