<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Process;

class LGTVMessenger
{
    public string $ip;

    public string $key;

    public function setTVIP($ip)
    {
        $this->ip = $ip;
    }

    public function setTVKey($key)
    {
        $this->key = $key;
    }

    public function send(string $message)
    {
        if (empty($this->ip) || empty($this->key)) {
            throw new Exception('TV not configured');
        }

        if (empty($message)) {
            throw new Exception('No message provided');
        }

        $exec = PythonExec::handle();

        Process::run("{$exec} -m pip install -r requirements.txt");

        $sendMessage = Process::run("{$exec} message.py -t {$this->ip} -m \"{$message}\" -k {$this->key}");

        return $sendMessage->errorOutput();
    }
}
