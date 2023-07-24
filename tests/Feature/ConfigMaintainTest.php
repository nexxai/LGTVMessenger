<?php

namespace Tests\Feature;

use App\Services\ConfigMaintain;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConfigMaintainTest extends TestCase
{
    #[Test]
    public function it_can_create_the_config_file_if_it_doesnt_exist(): void
    {
        $config_path = base_path().'/config/lgtvs.php';
        $config_path_backup = $config_path.md5(now());
        if (file_exists($config_path)) {
            rename($config_path, $config_path_backup);
        }

        $configMaintain = new ConfigMaintain();
        $configMaintain->create_lgtvs_file();

        $this->assertTrue(file_exists($config_path));

        $configFileContents = include $config_path;
        $this->assertTrue(is_array($configFileContents));

        if (file_exists($config_path_backup)) {
            rename($config_path_backup, $config_path);
        }
    }
}
