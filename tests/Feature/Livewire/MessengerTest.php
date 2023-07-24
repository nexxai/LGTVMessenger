<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\MessagePage;
use App\Services\LGTVMessenger;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MessengerTest extends TestCase
{
    public array $tvList;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tvList = [
            [
                'name' => 'Upstairs',
                'ip' => '127.0.0.1',
                'key' => 'abcd',
            ],
        ];
    }

    #[Test]
    public function a_user_can_visit_the_home_page(): void
    {
        $this->get('/')
            ->assertOk();
    }

    #[Test]
    public function it_knows_all_the_configured_tvs(): void
    {
        Livewire::test(MessagePage::class)
            ->set('tvList', $this->tvList)
            ->assertSee($this->tvList[0]['name']);
    }

    #[Test]
    public function if_a_message_is_not_set_an_validation_error_is_returned(): void
    {
        Livewire::test(MessagePage::class)
            ->set('messageToSend', '')
            ->call('sendMessage')
            ->assertHasErrors('messageToSend');

        Livewire::test(MessagePage::class)
            ->set('messageToSend', 'This is a good message')
            ->set('selectedTV', 0)
            ->call('sendMessage')
            ->assertHasNoErrors('messageToSend');
    }

    #[Test
    ]
    public function a_message_can_only_be_sent_between_one_and_five_times(): void
    {
        Livewire::test(MessagePage::class)
            ->set('messageToSend', 'This is a good message')
            ->set('sendNumOfTimes', 0)
            ->call('sendMessage')
            ->assertHasErrors('sendNumOfTimes');

        Livewire::test(MessagePage::class)
            ->set('messageToSend', 'This is a good message')
            ->set('sendNumOfTimes', 6)
            ->call('sendMessage')
            ->assertHasErrors('sendNumOfTimes');

        Livewire::test(MessagePage::class)
            ->set('messageToSend', 'This is a good message')
            ->set('sendNumOfTimes', 1)
            ->set('selectedTV', 0)
            ->call('sendMessage')
            ->assertHasNoErrors('sendNumOfTimes');
    }

    #[Test]
    public function it_can_send_a_message(): void
    {
        $spy = $this->spy(LGTVMessenger::class);

        $test = new MessagePage();
        $test->messageToSend = 'Good message';
        $test->sendNumOfTimes = 1;
        $test->tvList = $this->tvList;
        $test->selectedTV = 0;

        $test->sendMessage($spy);

        $spy->shouldHaveReceived()
            ->setTVIP('127.0.0.1')
            ->once();
        $spy->shouldHaveReceived()
            ->setTVKey('abcd')
            ->once();
    }

    #[Test]
    public function it_can_send_a_message_multiple_times(): void
    {
        $spy = $this->spy(LGTVMessenger::class);

        $test = new MessagePage();
        $test->messageToSend = 'Good message';
        $test->sendNumOfTimes = 2;
        $test->tvList = $this->tvList;
        $test->selectedTV = 0;

        $test->sendMessage($spy);

        $spy->shouldHaveReceived()
            ->send('Good message')
            ->twice();
    }
}
