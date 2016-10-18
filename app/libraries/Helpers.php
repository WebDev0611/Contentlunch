<?php

namespace App;

use Carbon\Carbon;
use Storage;
use File;

class Helpers {

    /**
     * Slugifies a given text.
     *
     * @param  string
     * @return string
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * This function receives two parameters and fetches
     * the related object from the database. i.e. if $key == 'content_id',
     * it will hit the Content table and fetch the Title of the Content
     * with id == $value.
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public static function getRelatedContentString($key, $value)
    {
        $classNames = [
            'content_type_id' => \App\ContentType::class,
            'connection_id'   => \App\Connection::class,
            'buying_stage_id' => \App\BuyingStage::class,
            'campaign_id'     => \App\Campaign::class,
            'user_id'         => \App\User::class
        ];

        $class = $classNames[$key];
        $object = $class::find($value);

        return (string) $object;
    }

    /**
     * Handles upload of profile pictures
     */
    public static function handleProfilePicture($user, $file)
    {
        $path = 'attachment/' . $user->id . '/profile/';

        // TODO: validate mime type
        $mime      = $file->getClientMimeType();

        $extension = $file->getClientOriginalExtension();
        $filename  = self::slugify($user->name) . '.' . $extension;
        $timestamp = Carbon::now('UTC')->format('Ymd_His');
        $fileDoc   = $timestamp . '_' . $filename;
        $fullPath  = $path . $fileDoc;

        Storage::put($fullPath, File::get($file));

        return Storage::url($fullPath);
    }

    /**
     * Generate v4 UUID
     *
     * Version 4 UUIDs are pseudo-random.
     */
    public static function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

