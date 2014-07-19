<?php namespace Launch\Scheduler;

use \DB;
use \Config;
use \Carbon\Carbon;

class Timezone
{
    /**
     * Set the timezone for PHP, Laravel and MySQL!
     * @param string $timezone The timezone to use in the form of '-08:00'
     */
    public static function set($timezone)
    {
        // set MySQL timezone
        DB::statement("SET time_zone='{$timezone}'");

        // convert -08:00 to "Americas/Los_Angeles" etc
        // note that it may not be reliable to store the timezone ID because
        // of ambiguity thanks to DST. But it should work for the life of this
        // request
        $offset = intval($timezone);
        $timezoneName = timezone_name_from_abbr(null, $offset * 3600, true);
        if ($timezoneName === false) $tz = timezone_name_from_abbr(null, $offset * 3600, false);

        // set Laravel timezone
        Config::set('app.timezone', $timezoneName);
        // set PHP timezone
        date_default_timezone_set($timezoneName);
    }
}

