<?php

namespace App\Helpers;

class TimeHelper
{
    /**
     * Format minutes into hours and minutes display
     * 
     * @param int|float $minutes
     * @return string
     */
    public static function formatDuration($minutes): string
    {
        $minutes = round($minutes);
        
        if ($minutes < 1) {
            return '0 minutes';
        }

        $hours = intval($minutes / 60);
        $remainingMinutes = $minutes % 60;

        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . ($hours === 1 ? ' hour' : ' hours');
        }

        if ($remainingMinutes > 0) {
            $parts[] = $remainingMinutes . ($remainingMinutes === 1 ? ' minute' : ' minutes');
        }

        return implode(' ', $parts);
    }

    /**
     * Format minutes into short format (e.g., "2h 30m")
     * 
     * @param int|float $minutes
     * @return string
     */
    public static function formatDurationShort($minutes): string
    {
        $minutes = round($minutes);
        
        if ($minutes < 1) {
            return '0m';
        }

        $hours = intval($minutes / 60);
        $remainingMinutes = $minutes % 60;

        $parts = [];

        if ($hours > 0) {
            $parts[] = $hours . 'h';
        }

        if ($remainingMinutes > 0) {
            $parts[] = $remainingMinutes . 'm';
        }

        return implode(' ', $parts);
    }
}
