<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

class DifferTest extends TestCase
{

    /**
     * @dataProvider gendiffStylishProvider
     */
    public function testGendiffStylish(string $path1, string $path2, string $expectedFile): void
    {
        $result = trim((gendiff($path1, $path2, 'stylish')));
        $expected =  trim(file_get_contents($expectedFile));
        $this->assertEquals($expected, $result);

        $resultDefault = trim((gendiff($path1, $path2)));
        $this->assertEquals($expected, $resultDefault);
        
    }
    public static function gendiffStylishProvider(){
        return [['./tests/fixtures/test1/json/file1.json', './tests/fixtures/test1/json/file2.json', './tests/fixtures/test1/expected.json'],
                ['./tests/fixtures/test1/yml/file1.yml', './tests/fixtures/test1/yml/file2.yml', './tests/fixtures/test1/expected.json'],
                ['./tests/fixtures/test2/json/file1.json', './tests/fixtures/test2/json/file2.json', './tests/fixtures/test2/expected.json'],
                ['./tests/fixtures/test2/yml/file1.yml', './tests/fixtures/test2/yml/file2.yml', './tests/fixtures/test2/expected.json'],
                ['./tests/fixtures/test3/json/file1.json', './tests/fixtures/test3/json/file2.json', './tests/fixtures/test3/expected.json'],
                ['./tests/fixtures/test3/yml/file1.yml', './tests/fixtures/test3/yml/file2.yml', './tests/fixtures/test3/expected.json'],
                ['./tests/fixtures/test4/json/file1.json', './tests/fixtures/test4/json/file2.json', './tests/fixtures/test4/expected.json'],
                ['./tests/fixtures/test4/yml/file1.yml', './tests/fixtures/test4/yml/file2.yml', './tests/fixtures/test4/expected.json'],
                ['./tests/fixtures/test5/json/file1.json', './tests/fixtures/test5/json/file2.json', './tests/fixtures/test5/expected.json'],
                ['./tests/fixtures/test5/yml/file1.yml', './tests/fixtures/test5/yml/file2.yml', './tests/fixtures/test5/expected.json']
    ];
    }

    /**
     * @dataProvider gendiffPlainProvider
     */
    public function testGendiffPlain($path1, $path2, $expectedPath)
    {
        $expected = trim(file_get_contents($expectedPath));
        $this->assertEquals($expected, trim(gendiff($path1, $path2, 'plain')));
    }
    public static function gendiffPlainProvider()
    {
        return [['./tests/fixtures/test1/json/file1.json', './tests/fixtures/test1/json/file2.json', './tests/fixtures/test1/expectedPlain'],
                ['./tests/fixtures/test1/yml/file1.yml', './tests/fixtures/test1/yml/file2.yml', './tests/fixtures/test1/expectedPlain'],
                ['./tests/fixtures/test2/json/file1.json', './tests/fixtures/test2/json/file2.json', './tests/fixtures/test2/expectedPlain'],
                ['./tests/fixtures/test2/yml/file1.yml', './tests/fixtures/test2/yml/file2.yml', './tests/fixtures/test2/expectedPlain'],
                ['./tests/fixtures/test3/json/file1.json', './tests/fixtures/test3/json/file2.json', './tests/fixtures/test3/expectedPlain'],
                ['./tests/fixtures/test3/yml/file1.yml', './tests/fixtures/test3/yml/file2.yml', './tests/fixtures/test3/expectedPlain'],
                ['./tests/fixtures/test4/json/file1.json', './tests/fixtures/test4/json/file2.json', './tests/fixtures/test4/expectedPlain'],
                ['./tests/fixtures/test4/yml/file1.yml', './tests/fixtures/test4/yml/file2.yml', './tests/fixtures/test4/expectedPlain'],
                ['./tests/fixtures/test5/json/file1.json', './tests/fixtures/test5/json/file2.json', './tests/fixtures/test5/expectedPlain'],
                ['./tests/fixtures/test5/yml/file1.yml', './tests/fixtures/test5/yml/file2.yml','./tests/fixtures/test5/expectedPlain']
    ];
    }

    /**
     * @dataProvider gendiffJsonProvider
     */
    public function testGendiffJson($path1, $path2, $expectedPath)
    {
        $this->assertJsonStringEqualsJsonFile($expectedPath, trim(gendiff($path1, $path2, 'json')));
    }
    public static function gendiffJsonProvider()
    {
        return [['./tests/fixtures/test1/json/file1.json', './tests/fixtures/test1/json/file2.json', './tests/fixtures/test1/expectedJson.json'],
                ['./tests/fixtures/test1/yml/file1.yml', './tests/fixtures/test1/yml/file2.yml', './tests/fixtures/test1/expectedJson.json'],
                ['./tests/fixtures/test2/json/file1.json', './tests/fixtures/test2/json/file2.json', './tests/fixtures/test2/expectedJson.json'],
                ['./tests/fixtures/test2/yml/file1.yml', './tests/fixtures/test2/yml/file2.yml', './tests/fixtures/test2/expectedJson.json'],
                ['./tests/fixtures/test3/json/file1.json', './tests/fixtures/test3/json/file2.json', './tests/fixtures/test3/expectedJson.json'],
                ['./tests/fixtures/test3/yml/file1.yml', './tests/fixtures/test3/yml/file2.yml', './tests/fixtures/test3/expectedJson.json'],
                ['./tests/fixtures/test4/json/file1.json', './tests/fixtures/test4/json/file2.json', './tests/fixtures/test4/expectedJson.json'],
                ['./tests/fixtures/test4/yml/file1.yml', './tests/fixtures/test4/yml/file2.yml', './tests/fixtures/test4/expectedJson.json'],
                ['./tests/fixtures/test5/json/file1.json', './tests/fixtures/test5/json/file2.json', './tests/fixtures/test5/expectedJson.json'],
                ['./tests/fixtures/test5/yml/file1.yml', './tests/fixtures/test5/yml/file2.yml','./tests/fixtures/test5/expectedJson.json']
    ];
    }
}
