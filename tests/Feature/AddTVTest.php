<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AddTVTest extends TestCase
{
    public string $ip;

    public string $keyName;

    public string $name;

    public string $env_key;

    public string $env_path;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ip = '127.0.0.1';
        $this->keyName = 'TV_KEY';
        $this->name = 'Example';
        $this->env_key = str($this->name)->snake()->upper().'_TV_KEY';
        $this->env_path = base_path().'/.env';
    }

    #[Test]
    public function it_can_add_a_tv(): void
    {
        $this->artisan('lg:add')
            ->expectsQuestion('Friendly name for the TV', $this->name)
            ->expectsQuestion('IP address of TV', $this->ip)
            ->expectsOutput("Added TV {$this->name} to the configuration")
            ->assertExitCode(0);

        $config = include base_path().'/config/lgtvs.php';

        $found = false;
        foreach ($config as $tv) {
            if ($tv['name'] == $this->name) {
                $found = true;
            }
        }

        $this->assertTrue($found);

        $env = file_get_contents($this->env_path);

        $this->assertStringContainsString($this->env_key, $env);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $env = file_get_contents($this->env_path);

        $env = preg_replace("/{$this->env_key}\=$/", '', $env);
        $env = preg_replace("/\n$/", '', $env);

        $env = file_put_contents($this->env_path, $env);
    }
}
