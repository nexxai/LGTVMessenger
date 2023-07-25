<?php

namespace App\Http\Livewire;

use App\Services\LGTVMessenger;
use Livewire\Component;

class MessagePage extends Component
{
    public bool $tv_alive = true;

    public string $messageToSend;

    public array $tvList;

    public int $sendNumOfTimes = 1;

    public int $selectedTVIndex;

    public bool $success = false;

    public bool $sending = false;

    public $precanned = [];

    public function mount()
    {
        $this->getTVs();

        $this->getPrecannedMessages();
    }

    public function sendMessage(LGTVMessenger $messenger)
    {
        $this->validate();

        $this->sending = true;

        $this->configureMessenger($messenger);

        $this->tv_alive = $this->checkIsTVAlive($messenger);

        if ($this->tv_alive) {
            $this->sendMessages($messenger);
        }

        $this->sending = false;
    }

    public function reset_tv_alive_status()
    {
        $this->tv_alive = true;
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

    /**
     * @return void
     */
    public function getPrecannedMessages(): void
    {
        $precanned = config('precanned');
        if (!empty($precanned)) {
            $this->precanned = $precanned;
        }
    }

    /**
     * @return void
     */
    public function getTVs(): void
    {
        $tvs = config('lgtvs');

        if (!empty($tvs)) {
            $this->tvList = $tvs;
            $this->selectedTVIndex = array_key_first($tvs);
        }
    }

    /**
     * @param LGTVMessenger $messenger
     * @return void
     * @throws \Exception
     */
    public function sendMessages(LGTVMessenger $messenger): void
    {
        for ($i = 0; $i < $this->sendNumOfTimes; $i++) {
            $messenger->send($this->messageToSend);
        }
        $this->messageToSend = '';
        $this->success = true;
    }

    /**
     * @param LGTVMessenger $messenger
     * @return bool
     */
    public function checkIsTVAlive(LGTVMessenger $messenger): bool
    {
        return $messenger->ping();
    }

    /**
     * @param LGTVMessenger $messenger
     * @return void
     */
    public function configureMessenger(LGTVMessenger $messenger): void
    {
        $selectedTV = $this->tvList[$this->selectedTVIndex];
        $messenger->setTVIP($selectedTV['ip']);
        $messenger->setTVKey($selectedTV['key']);
    }
}
