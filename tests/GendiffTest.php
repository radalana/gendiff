<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;


use function Code\Gendiff\gendiff;

class GendiffTest extends TestCase {
    public function testGendiff(): void
    {
        $filePath1 = './tests/fixtures/test1/file1.json';
        $filePath2 = './tests/fixtures/test1/file2.json';
        $expected = "{\n- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22\n- timeout: 50\n+ timeout: 20\n+ verbose: true\n}\n";
        $this->assertEquals($expected, gendiff($filePath1, $filePath2));
    }

    public function testGendiff2(): void
    {
        $filePath1 = './tests/fixtures/test2/file1.json';
        $filePath2 = './tests/fixtures/test2/file2.json';
        $expected = "{\n+ api-key: 550e\n+ api-version: v1\n- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22\n- timeout: 50\n+ timeout: 20\n+ verbose: true\n}\n";
        $this->assertEquals($expected, gendiff($filePath1, $filePath2));
    }

    //**same keys, all values changed*/
    public function testGendiff3(): void
    {
        $filePath1 = './tests/fixtures/test3/file1.json';
        $filePath2 = './tests/fixtures/test3/file2.json';
        $expected = "{\n- api-key: 550e\n+ api-key: 770k\n- api-version: v1\n+ api-version: v2\n- host: hexlet.io\n+ host: www.example.com\n- timeout: 20\n+ timeout: 50\n- verbose: true\n+ verbose: false\n}\n";
        $this->assertEquals($expected, gendiff($filePath1, $filePath2));
    }

    /** First file is empty*/
    public function testEmptyFile(): void
    {
        $filePath1 = './tests/fixtures/test4/file1.json';
        $filePath2 = './tests/fixtures/test4/file2.json';
        $this->expectExceptionMessage("{$filePath1} is empty!");
        gendiff($filePath1, $filePath2);
    }

    public function testIdenticalFiles(): void
    {
        $filePath1 = './tests/fixtures/test5/file1.json';
        $filePath2 = './tests/fixtures/test5/file2.json';
        $expected = "{\n  host: hexlet.io\n  timeout: 20\n  verbose: true\n}\n";
        $this->assertEquals($expected, gendiff($filePath1, $filePath2)); 
    }
}

