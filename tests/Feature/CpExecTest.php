<?php

namespace Tests\Feature;

use App\Services\CpExec;
use App\Services\OS;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CpExecTest extends TestCase
{
    #[Test]
    public function it_can_use_the_correct_copy_command_for_the_os(): void
    {
        $test_runner_os = OS::detect();

        if ($test_runner_os == OS::WINDOWS) {
            $this->assertEquals('copy', CpExec::handle());
        } else {
            $this->assertEquals('cp', CpExec::handle());
        }
    }
}
