<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\MessagePage;
use App\Services\LGTVMessenger;
use Livewire\Livewire;
use Mockery;
use Mockery\MockInterface;
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
        config()->set('lgtvs', $this->tvList);

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
        config()->set('lgtvs', $this->tvList);

        Livewire::test(MessagePage::class)
            ->set('messageToSend', '')
            ->call('sendMessage')
            ->assertHasErrors('messageToSend');

        Livewire::test(MessagePage::class)
            ->set('messageToSend', 'This is a good message')
            ->set('selectedTVIndex', 0)
            ->call('sendMessage')
            ->assertHasNoErrors('messageToSend');
    }

    #[Test
    ]
    public function a_message_can_only_be_sent_between_one_and_five_times(): void
    {
        config()->set('lgtvs', $this->tvList);

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
            ->set('selectedTVIndex', 0)
            ->call('sendMessage')
            ->assertHasNoErrors('sendNumOfTimes');
    }

    #[Test]
    public function it_can_send_a_message(): void
    {
        $mock = Mockery::mock(LGTVMessenger::class, function (MockInterface $mock) {
            $mock->shouldReceive()
                ->ping()
                ->once()
                ->andReturn(true)
                ->shouldReceive()
                ->setTVIP('127.0.0.1')
                ->once()
                ->shouldReceive()
                ->setTVKey('abcd')
                ->once()
                ->shouldReceive()
                ->send('Good message')
                ->once();
        });

        $test = new MessagePage();
        $test->messageToSend = 'Good message';
        $test->sendNumOfTimes = 1;
        $test->tvList = $this->tvList;
        $test->selectedTVIndex = 0;
        $test->host_alive = true;

        $test->sendMessage($mock);
    }

    #[Test]
    public function it_can_send_a_message_multiple_times(): void
    {
        $mock = Mockery::mock(LGTVMessenger::class, function (MockInterface $mock) {
            $mock->shouldReceive()
                ->setTVIP('127.0.0.1')
                ->shouldReceive()
                ->setTVKey('abcd')
                ->shouldReceive()
                ->ping()
                ->andReturn(true)
                ->shouldReceive()
                ->send('Good message')
                ->twice();
        });

        $test = new MessagePage();
        $test->messageToSend = 'Good message';
        $test->sendNumOfTimes = 2;
        $test->tvList = $this->tvList;
        $test->selectedTVIndex = 0;
        $test->host_alive = true;

        $test->sendMessage($mock);
    }

    #[Test]
    public function it_can_set_the_number_of_times_to_send_a_message(): void
    {
        Livewire::test(MessagePage::class)
            ->call('sendTimes', 4)
            ->assertSet('sendNumOfTimes', 4);
    }

    #[Test]
    public function if_there_are_no_precanned_messages_do_not_show_that_section(): void
    {
        config()->set('precanned', []);
        Livewire::test(MessagePage::class)
            ->assertDontSee('precanned');
    }

    #[Test]
    public function if_there_are_configured_precanned_messages_show_that_section(): void
    {
        config()->set('precanned', [0 => 'Lili is awake']);
        Livewire::test(MessagePage::class)
            ->assertSee('precanned');
    }

    #[Test]
    public function if_there_are_configured_precanned_messages_they_should_be_visible(): void
    {
        $message1 = 'Can you come upstairs';
        $message2 = 'Check your phone';

        config()->set('precanned', [0 => $message1, 1 => $message2]);
        Livewire::test(MessagePage::class)
            ->assertSee($message1)
            ->assertSee($message2);
    }

    #[Test]
    public function it_can_reset_the_tvs_alive_status(): void
    {
        Livewire::test(MessagePage::class)
            ->set('tv_alive', false)
            ->call('reset_tv_alive_status')
            ->assertSet('tv_alive', true);

    }
}
