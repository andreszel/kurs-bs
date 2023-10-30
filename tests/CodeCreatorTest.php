<?php

namespace App\Tests;

use App\Service\CodeCreator;
use PHPUnit\Framework\TestCase;

class CodeCreatorTest extends TestCase
{
    public function test_create_code_use_code_creator_service(): void
    {
        $codeCreator = new CodeCreator();
        $code = $codeCreator->createCode('test');

        $this->assertIsString($code);
        $this->assertEquals(9, strlen($code));
    }
}
