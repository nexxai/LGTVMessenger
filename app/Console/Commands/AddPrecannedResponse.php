<?php

namespace App\Console\Commands;

use App\Services\ModifyConfig;
use Illuminate\Console\Command;

class AddPrecannedResponse extends Command
{
    protected $signature = 'lg:add-response {
                            response : The response to store, wrapped in double quotes }';

    protected $description = 'Add a precanned response (e.g. "Lili is awake")';

    public string $file_path;

    public function __construct()
    {
        parent::__construct();
        $this->file_path = base_path().'/config/precanned.php';
    }

    public function handle()
    {
        $config = new ModifyConfig($this->file_path);

        $config->add($this->argument('response'));

        $this->components->info("Added '{$this->argument('response')}' to the list");
    }
}
