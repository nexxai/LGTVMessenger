<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PrecannedResponsesTest extends TestCase
{
    public string $file_path;

    public string $file_path_backup;

    protected function setUp(): void
    {
        parent::setUp();
        $this->file_path = base_path().'/config/precanned.php';
        $this->file_path_backup = $this->file_path.md5(now());

        if (file_exists($this->file_path)) {
            rename($this->file_path, $this->file_path_backup);
        }
    }

    #[Test]
    public function it_can_add_common_responses_to_a_stored_file(): void
    {
        $response = 'Lili is awake';

        $this->artisan('lg:add-response', ['response' => $response])
            ->assertExitCode(0);

        $precanned_file_path = base_path().'/config/precanned.php';
        $precanned_file = file_get_contents($precanned_file_path);

        $this->assertStringContainsString($response, $precanned_file);

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
