<?php

namespace App\Services;

class OS
{
    const WINDOWS = 1;
    const NONWINDOWS = 2;

    public static function detect(): int
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return self::WINDOWS;
        } else {
            return self::NONWINDOWS;
        }
    }
}
