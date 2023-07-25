<?php

namespace App\Console\Commands;

use App\Services\ModifyConfig;
use Illuminate\Console\Command;

class RemovePrecannedResponse extends Command
{
    protected $signature = 'lg:remove-response';

    protected $description = 'Need to remove a saved response?  Do it here.';

    public string $file_path;

    public function __construct()
    {
        parent::__construct();

        $this->file_path = base_path() . '/config/precanned.php';
    }

    public function handle()
    {

        $config = new ModifyConfig($this->file_path);
        $responseList = $config->read();

        if (isset($responseList) && count($responseList) < 1) {
            $this->components->warn('No stored responses found');
            return 0;
        }

        $response = $this->choice('Which response would you like to remove', $responseList);
        $response = intval($response);


        $this->components->warn('About to remove:');
        $this->line('    ' . $responseList[$response]);

        $confirm = $this->components->confirm('Are you sure', false);

        if ($confirm) {
            $config->remove($response);
        }
    }
}
