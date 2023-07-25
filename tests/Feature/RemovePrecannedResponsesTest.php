<?php

namespace Tests\Feature;

use App\Services\ModifyConfig;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemovePrecannedResponsesTest extends TestCase
{
    public string $file_path;

    public string $file_path_backup;

    protected function setUp(): void
    {
        parent::setUp();
        $this->file_path = base_path() . '/config/precanned.php';
        $this->file_path_backup = $this->file_path . md5(now());

        if (file_exists($this->file_path)) {
            rename($this->file_path, $this->file_path_backup);
        }
    }

    #[Test]
    public function it_can_remove_precanned_responses(): void
    {
        $message1 = 'Lili is awake';
        $message2 = 'Can you come upstairs';
        $message3 = 'Turn down the TV';
        $message4 = 'I am going to bed';

        $responses = [0 => $message1, 1 => $message2, 2 => $message3, 3 => $message4];
        $responseToRemove = "1";

        $file = new ModifyConfig($this->file_path);
        $file->create_blank_file();
        foreach ($responses as $response) {
            $file->add($response);
        }

        $this->artisan('lg:remove-response')
            ->expectsChoice('Which response would you like to remove', $responseToRemove, $responses)
            ->expectsConfirmation('Are you sure', 'yes');

        $remainingResponses = $file->read();

        $found = false;
        foreach ($remainingResponses as $key => $remainingResponse) {
            if ($remainingResponse === $responses[$responseToRemove]) {
                $found = true;
            }
        }
        $this->assertFalse($found);
    }

    #[Test]
    public function if_there_are_no_responses_you_cannot_remove_any(): void
    {

        $file = new ModifyConfig($this->file_path);
        $file->create_blank_file();

        $this->artisan('lg:remove-response')
            ->expectsOutputToContain('No stored responses found')
            ->assertExitCode(0);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unlink($this->file_path);
        if (file_exists($this->file_path_backup)) {
            rename($this->file_path_backup, $this->file_path);
        }
    }
}
