<?php

namespace App\Services;

class PythonExec
{
    public static function get()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return 'python';
        } else {
            return 'python3';
        }
    }
}
