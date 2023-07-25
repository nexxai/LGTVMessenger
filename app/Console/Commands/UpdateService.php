<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class UpdateService extends Command
{
    protected $signature = 'lg:update';

    protected $description = 'Get the latest version of LG TV Messenger';

    public function handle()
    {
        Process::run('git pull');
        Process::run('composer update');
        Process::run('npm run build');
    }
}
