<?php

namespace App\Services;

class PythonExec
{
    public static function handle(): string
    {
        $os = OS::detect();

        if($os == OS::WINDOWS) {
            return 'python';
        } else {
            return 'python3';
        }
    }
}
