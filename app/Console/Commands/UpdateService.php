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
        $this->components->info('Running: git pull');
        Process::run('git pull');
        $this->components->info('Running: composer update');
        Process::run('composer update');
        $this->components->info('Running: npm run build');
        Process::run('npm run build');
    }
}
