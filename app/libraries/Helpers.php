<?php

namespace App;

use Carbon\Carbon;
use Storage;
use File;
use Config;
use Auth;

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
        $filename  = self::slugify($user->name);

        return self::handleUpload($file, $filename, $path);
    }

    public static function handleAccountPicture($account, $file)
    {
        $path = 'attachment/' . $account->id . '/account/';
        $filename  = self::slugify($account->name);

        return self::handleUpload($file, $filename, $path);
    }

    public static function handleTmpUpload($file, $useOwnFileName = false)
    {
        $path = 'attachment/_tmp/';
        $filename = $useOwnFileName ?
            self::slugify($file->getClientOriginalName()) :
            str_random(32);

        return self::handleUpload($file, $filename, $path);
    }

    public static function userImagesFolder($userId = null)
    {
        if (!$userId) {
            $userId = Auth::id();
        }

        return "attachment/$userId/images";
    }

    public static function userFilesFolder($userId = null)
    {
        if (!$userId) {
            $userId = Auth::id();
        }

        return "attachment/$userId/files";
    }

    public static function userTasksFolder($userId = null)
    {
        if (!$userId) {
            $userId = Auth::id();
        }

        return "attachment/$userId/files";
    }

    public static function tempFolder()
    {
        return 'attachment/_tmp/';
    }

    public static function handleUpload($file, $filename, $path)
    {
        $mime = $file->getClientMimeType();

        $extension = $file->getClientOriginalExtension();
        $filename = $filename . '.' . $extension;
        $timestamp = Carbon::now('UTC')->format('Ymd_His');
        $fullPath  = $path . $timestamp . '_' . $filename;

        Storage::put($fullPath, File::get($file));

        return Storage::url($fullPath);
    }

    public static function moveFileToFolder($fileUrl, $folder)
    {
        $fileName = substr(strstr($fileUrl, '_tmp/'), 5);
        $newPath = $folder . $fileName;
        $s3Path = Helpers::s3Path($fileUrl);
        Storage::move($s3Path, $newPath);

        return $newPath;
    }

    public static function s3Path($fullUrl)
    {
        $bucket = Config::get('filesystems.disks.s3.bucket') . '/';
        $splitUrl = explode($bucket, $fullUrl);

        return $splitUrl[1];
    }

    public static function extensionFromS3Path($s3Path)
    {
        $extension = collect(explode('.', $s3Path))->last();

        if (strstr($extension, '/')) {
            $extension = '';
        }

        return $extension;
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

    public static function isCollaborator(array $options, $isCollaborator = true)
    {
        if (!$isCollaborator) {
            $options['disabled'] = 'disabled';
        }

        return $options;
    }
}

