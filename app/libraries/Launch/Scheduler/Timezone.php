<?php namespace Launch\Scheduler;

class Timezone
{
    /**
     * Set the timezone for PHP, Laravel and MySQL!
     * @param string $timezone The timezone to use in the form of '-08:00'
     */
    public static function set($timezone)
    {
        // set MySQL timezone
        \DB::statement("SET time_zone='{$timezone}'");

        // convert -07:00 to "Americas/Los_Angeles" etc
        $offset = intval($timezone);
        $tz = timezone_name_from_abbr(null, $offset * 3600, true);
        if ($tz === false) $tz = timezone_name_from_abbr(null, $offset * 3600, false);

        // set Laravel timezone
        \Config::set('app.timezone', $tz);
        // set PHP timezone
        date_default_timezone_set($tz);
    }
}

