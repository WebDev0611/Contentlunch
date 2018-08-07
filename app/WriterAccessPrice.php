<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WriterAccessPrice extends Model
{
    public $table = 'writer_access_prices';

    protected $softDelete = true;

    protected $fillable = ['id', 'asset_type_id', 'writer_level', 'wordcount', 'fee'];

    public static $rules = [
        'asset_type_id' => 'required',
        'writer_level' => 'required',
        'wordcount' => 'required',
        'fee' => 'required',
    ];

    public function writer_access_asset_type()
    {
        return $this->hasOne('WriterAccessAssetType', 'writer_access_id', 'asset_type_id');
    }

    public static function wordcounts()
    {
        return self::select('wordcount')
            ->groupBy('wordcount')
            ->pluck('wordcount');
    }

    public static function availableWordcountsByAssetType($assetTypeId)
    {
        return self::select('wordcount')
            ->distinct()
            ->where('asset_type_id', $assetTypeId)
            ->get();
    }
}
