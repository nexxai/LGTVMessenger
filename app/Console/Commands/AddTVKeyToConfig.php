<?php

namespace App\Console\Commands;

use App\Services\ModifyConfig;
use App\Services\PythonExec;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class AddTVKeyToConfig extends Command
{
    protected $signature = 'lg:add';

    protected $description = 'Pair this app with your TV';

    public string $config_file_path;

    public function __construct()
    {
        parent::__construct();

        $this->config_file_path = base_path().'/config/lgtvs.php';
    }

    public function handle()
    {
        $name = $this->ask('Friendly name for the TV');
        $ip = $this->ask('IP address of TV');

        $this->components->info('Attempting to pair with TV');

        $exec = PythonExec::handle();
        $key = Process::timeout(120)->start("{$exec} public/key.py -t {$ip}");

        $counter = 0;
        while ($key->running()) {
            if ($counter % 5 == 0) {
                $this->components->warn('You must accept the prompt on screen to pair this app with the TV.');
            }

            sleep(1);
            $counter++;
        }

        $client_key = trim($key->output());

        $tvInstance = [
            'name' => $name,
            'key' => $client_key,
            'ip' => $ip,
        ];

        $config = new ModifyConfig($this->config_file_path);
        $config->add($tvInstance);

        $this->components->info("Added TV '{$name}' to the configuration");
    }
}
