<?php

namespace App\Services;

class CpExec
{
    public static function handle(): string
    {
        $os = OS::detect();

        if ($os == OS::WINDOWS) {
            return 'copy';
        } else {
            return 'cp';
        }
    }
}
