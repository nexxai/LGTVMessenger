<?php

namespace App\Http\Livewire;

use App\Services\LGTVMessenger;
use Exception;
use Livewire\Component;

class MessagePage extends Component
{
    public string $messageToSend;

    public array $tvList;

    public string $errorWhenSending;

    public int $sendNumOfTimes = 1;

    public $selectedTV = 0;

    public bool $success = false;

    public bool $sending = false;

    public function mount()
    {
        $tvs = config('lgtvs');

        if (! empty($tvs)) {
            $this->tvList = $tvs;
        }
    }

    public function sendMessage(LGTVMessenger $messenger)
    {
        $this->validate();

        $this->sending = true;

        $selectedTV = $this->tvList[$this->selectedTV];
        $messenger->setTVIP($selectedTV['ip']);
        $messenger->setTVKey($selectedTV['key']);

        try {
            for ($i = 0; $i < $this->sendNumOfTimes; $i++) {
                $messenger->send($this->messageToSend);
            }
            $this->messageToSend = '';
            $this->success = true;
            $this->sending = false;
        } catch (Exception $e) {
            $this->errorWhenSending = $e->getMessage();
        }
    }

    public function sendTimes(int $numOfTimes)
    {
        $this->sendNumOfTimes = $numOfTimes;
    }

    public function render()
    {
        return view('livewire.message-page');
    }

    protected $rules = [
        'messageToSend' => 'required|string|min:2',
        'sendNumOfTimes' => 'required|min:1|max:5|integer',
        'tvList' => 'required',
    ];
}
