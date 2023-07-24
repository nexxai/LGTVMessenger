<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class EstablishTVKey extends Command
{
    protected $signature = 'lg:key
                            { ip : The IP of the TV }';

    protected $description = 'Pair this app with your TV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->newLine();

        $this->line('Step 1');
        $this->line('------');
        $this->line('On your TV, there will be an alert saying something like:');
        $this->newLine(2);
        $this->info('    If you accept this connection, LG Remote App will be able');
        $this->info('    to access and store information about your TV');
        $this->newLine(2);

        $key = Process::timeout(120)->start("python3 public/key.py -t {$this->argument('ip')}");

        $counter = 0;
        while ($key->running()) {
            if ($counter % 5 == 0) {
                $this->warn('You must accept the prompt on screen to pair this app with the TV.');
            }

            sleep(1);
            $counter++;
        }

        $this->newLine(2);
        $this->line('Step 2');
        $this->line('------');
        $this->line('Paste the following line into the .env file:');
        $this->newLine();
        $this->info($key->output());
    }
}
