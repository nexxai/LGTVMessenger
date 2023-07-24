<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Process;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AddTVTest extends TestCase
{
    public string $ip;

    public string $keyName;

    public string $name;

    public string $config_path;

    public string $config_backup_path;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ip = '127.0.0.1';
        $this->keyName = 'TV_KEY';
        $this->name = 'Example';
        $this->config_path = base_path().'/config/lgtvs.php';
        $this->config_backup_path = $this->config_path.'.'.md5(now()).'.bak';

        if (file_exists($this->config_path)) {
            copy($this->config_path, $this->config_backup_path);
        }

        $instance = [
            'ip' => $this->ip,
            'name' => $this->name,
            'key' => md5('example'),
        ];

        config('lgtvs')[] = $instance;
    }

    #[Test]
    public function it_can_add_a_tv(): void
    {
        Process::fake();

        $this->artisan('lg:add')
            ->expectsQuestion('Friendly name for the TV', $this->name)
            ->expectsQuestion('IP address of TV', $this->ip)
            ->assertExitCode(0);

        $config = include $this->config_path;

        $found = false;
        foreach ($config as $tv) {
            if ($tv['name'] == $this->name) {
                $found = true;
            }
        }

        $this->assertTrue($found);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        rename($this->config_backup_path, $this->config_path);
    }
}
