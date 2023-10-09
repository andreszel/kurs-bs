<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class CodeGenerator
{
    public function __construct(private Filesystem $filesystem, private string $codePrefix) {
    }

    public function generate(): string
    {
        $code = $this->codePrefix . rand(1000,9000);
        $direcotry = 'codes';
        $filename = $code . '.txt';
        $pathFile = $direcotry . '/' . $filename;

        $this->filesystem->mkdir($direcotry);
        $this->filesystem->touch($pathFile);
        file_put_contents($pathFile, $code);

        return $code;
    }
}