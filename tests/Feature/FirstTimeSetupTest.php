<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FirstTimeSetupTest extends TestCase
{
    #[Test]
    public function it_can_make_the_env_file_if_it_doesnt_exist(): void
    {
        $env_path = base_path().'/.env';
        $env_path_backup = $env_path.md5(now());
        if (file_exists($env_path)) {
            rename($env_path, $env_path_backup);
        }

        $this->artisan('lg:first-time')
            ->expectsQuestion('Friendly name for the TV', 'Example name')
            ->expectsQuestion('IP address of TV', '127.0.0.1')
            ->assertExitCode(0);

        $this->assertTrue(file_exists($env_path));

        if (file_exists($env_path_backup)) {
            rename($env_path_backup, $env_path);
        }
    }

    #[Test]
    public function it_can_make_the_config_file_if_it_doesnt_exist(): void
    {
        $config_path = base_path().'/config/lgtvs.php';
        $config_path_backup = $config_path.md5(now());
        if (file_exists($config_path)) {
            rename($config_path, $config_path_backup);
        }

        $this->artisan('lg:first-time')
            ->expectsQuestion('Friendly name for the TV', 'Example name')
            ->expectsQuestion('IP address of TV', '127.0.0.1')
            ->assertExitCode(0);

        $this->assertTrue(file_exists($config_path));

        if (file_exists($config_path_backup)) {
            rename($config_path_backup, $config_path);
        }
    }
}
