<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Code\Gendiff\gendiff;

class GendiffTest extends TestCase
{
    public function testGendiff(): void
    {
        $filePath1 = './tests/fixtures/file1.json';//tests/fixtures/file2.json
        $filePath2 = './tests/fixtures/file2.json';
        $expected = "{\n- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22\n- timeout: 50\n+ timeout: 20\n+ verbose: true\n}\n";
        
        $this->assertEquals($expected, gendiff($filePath1, $filePath2));  

    }
}