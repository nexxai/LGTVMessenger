<?php

namespace Tests\Feature;

use App\Services\LGTVMessenger;
use Exception;
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
        $message = 'This will be sent to the TV';

        $messenger->ip = '127.0.0.1';
        $messenger->key = 'abcd';
        $messenger->send('');
    }
}
