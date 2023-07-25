<?php

namespace Tests\Feature;

use App\Http\Livewire\MessagePage;
use Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GeneralUsageTest extends TestCase
{
    #[Test]
    public function if_the_tv_is_off_or_unavailable_the_ui_should_reflect_that(): void
    {
        $tv = [
            [
                'name' => 'Example',
                'key' => 'abcd',
                'ip' => '100.100.100.100',
            ],
        ];

        Livewire::test(MessagePage::class)
            ->set('messageToSend', 'Example')
            ->set('tvList', $tv)
            ->set('sendNumOfTimes', 1)
            ->set('selectedTVIndex', '0')
            ->call('sendMessage')
            ->assertSee('TV not available')
            ->assertSee('Could not send message');
    }
}
