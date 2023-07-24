<?php

namespace Tests\Feature;

use App\Services\LGTVMessenger;
use Exception;
use Illuminate\Support\Facades\Process;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LGTVMessengerTest extends TestCase
{
    #[Test]
    public function trying_to_send_without_an_ip_results_in_an_exception(): void
    {
        $this->expectException(Exception::class);

        $messenger = new LGTVMessenger();
        $message = 'This will be sent to the TV';

        $messenger->ip = '';
        $messenger->key = 'abcd';
        $messenger->send($message);
    }

    #[Test]
    public function trying_to_send_without_a_key_results_in_an_exception(): void
    {
        $this->expectException(Exception::class);

        $messenger = new LGTVMessenger();
        $message = 'This will be sent to the TV';

        $messenger->ip = '127.0.0.1';
        $messenger->key = '';
        $messenger->send($message);
    }

    #[Test]
    public function trying_to_send_without_a_message_results_in_an_exception(): void
    {
        $this->expectException(Exception::class);

        $messenger = new LGTVMessenger();

        $messenger->ip = '127.0.0.1';
        $messenger->key = 'abcd';
        $messenger->send('');
    }

    #[Test]
    public function it_can_send_a_message_to_a_tv(): void
    {
        Process::fake();
        Process::preventStrayProcesses();

        $messenger = new LGTVMessenger();
        $message = 'This will be sent to the TV';

        $messenger->ip = '127.0.0.1';
        $messenger->key = 'abcd';

        $this->assertEquals('', $messenger->send($message));
    }

    #[Test]
    public function if_it_cannot_send_a_message_to_a_tv_the_erroroutput_is_returned(): void
    {
        Process::fake([
            '*' => Process::result(
                errorOutput: 'Test error output',
                exitCode: 1,
            ),
        ]);
        Process::preventStrayProcesses();

        $messenger = new LGTVMessenger();
        $message = 'This will be sent to the TV';

        $messenger->ip = '127.0.0.1';
        $messenger->key = 'abcd';

        $this->assertEquals("Test error output\n", $messenger->send($message));
    }
}
