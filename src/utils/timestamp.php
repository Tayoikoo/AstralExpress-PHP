<?php

namespace AstralPHP\utils;

class timestamp
{
    /**
     * Get the current timestamp in milliseconds since the Unix epoch.
     *
     * @return int
     */
    public static function cur_timestamp_ms(): int
    {
        // Use microtime to get the current time with microseconds, multiply by 1000 for milliseconds
        return (int)(microtime(true) * 1000);
    }

    /**
     * Get the current timestamp in seconds since the Unix epoch.
     *
     * @return int
     */
    public static function cur_timestamp_seconds(): int
    {
        // Use time() to get the current timestamp in seconds
        return time();
    }
}
