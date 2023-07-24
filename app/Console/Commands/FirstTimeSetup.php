<?php

namespace App\Console\Commands;

use App\Services\ConfigMaintain;
use App\Services\CpExec;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class FirstTimeSetup extends Command
{
    protected $signature = 'lg:first-time';

    protected $description = 'Prepare the environment for the first time';

    public function handle()
    {
        if (! file_exists(base_path().'/.env')) {
            $copyCommand = CpExec::handle();
            Process::run("{$copyCommand} .env.example .env");
            $this->components->info('.env file created');
        }

        if (! file_exists(base_path().'/config/lgtvs.php')) {
            $config = new ConfigMaintain();
            $config->create_lgtvs_file();
            $this->components->info('Config file created');
        }

        $this->call('lg:add');

        $this->call('key:generate');
    }
}
