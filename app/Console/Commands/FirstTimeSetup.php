<?php

namespace App\Console\Commands;

use App\Services\CpExec;
use App\Services\ModifyConfig;
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

        $config_file_path = base_path().'/config/lgtvs.php';
        if (! file_exists($config_file_path)) {
            $config = new ModifyConfig($config_file_path);
            $config->create_blank_file();
            $this->components->info('Config file created');
        }

        $this->call('lg:add');

        $this->call('key:generate');
    }
}
