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

        Process::run('python -m pip install -r requirements.txt');

        $sendMessage = Process::run("python message.py -t {$this->ip} -m \"{$message}\" -k {$this->key}");

        return $sendMessage->output();
    }
}
