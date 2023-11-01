<?php

namespace App\Tests\Service;

use App\Service\CodeCreator;
use App\Service\CodeGenerator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

class CodeGeneratorTest extends KernelTestCase
{
    public function test_generate_code_user_code_generator_service(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $fileSystem = $container->get(Filesystem::class);
        $codeCreator = $container->get(CodeCreator::class);

        $codeGenerator = new CodeGenerator(
            $fileSystem,
            $codeCreator,
            'ABC'
        );

        $code = $codeGenerator->generate();

        $this->assertSame('test', $kernel->getEnvironment());
        $this->assertIsString($code);
        $this->assertMatchesRegularExpression('/[A-Z]{3}-[0-9]{4}/', $code);
        
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
