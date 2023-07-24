<?php

namespace Tests\Feature;

use App\Services\OS;
use App\Services\PythonExec;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PythonTest extends TestCase
{
    #[Test]
    public function it_can_use_the_correct_copy_command_for_the_os(): void
    {
        $test_runner_os = OS::detect();

        if ($test_runner_os == OS::WINDOWS) {
            $this->assertEquals('python', PythonExec::handle());
        } else {
            $this->assertEquals('python3', PythonExec::handle());
        }
    }
}
