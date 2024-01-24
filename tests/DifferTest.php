<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

class DifferTest extends TestCase
{
    private const PATH = './tests/fixtures/test';
    /**
     * @dataProvider gendiffStylishProvider
     */
    public function testGendiffStylish(string $path1, string $path2, string $expectedFile): void
    {
        $result = trim((gendiff($path1, $path2, "stylish")));
        $expected =  trim(file_get_contents($expectedFile));
        $this->assertEquals($expected, $result);

        $resultDefault = trim((gendiff($path1, $path2)));
        $this->assertEquals($expected, $resultDefault);
    }
    /**
    * @return array<array<string>>
    */
    public static function gendiffStylishProvider(): array
    {
        $path = self::PATH;//чтобы линтер не ругался на длинные строки
        return [["{$path}1/json/file1.json",
        "{$path}1/json/file2.json", "{$path}1/expected.json"],
                ["{$path}1/yml/file1.yml",
                "{$path}1/yml/file2.yml", "{$path}1/expected.json"],
                ["{$path}2/json/file1.json"
                , "{$path}2/json/file2.json", "{$path}2/expected.json"],
                ["{$path}2/yml/file1.yml",
                "{$path}2/yml/file2.yml", "{$path}2/expected.json"],
                ["{$path}3/json/file1.json",
                 "{$path}3/json/file2.json", "{$path}3/expected.json"],
                ["{$path}3/yml/file1.yml",
                 "{$path}3/yml/file2.yml", "{$path}3/expected.json"],
                ["{$path}4/json/file1.json",
                 "{$path}4/json/file2.json", "{$path}4/expected.json"],
                ["{$path}4/yml/file1.yml",
                 "{$path}4/yml/file2.yml", "{$path}4/expected.json"],
                ["{$path}5/json/file1.json",
                 "{$path}5/json/file2.json", "{$path}5/expected.json"],
                ["{$path}5/yml/file1.yml",
                 "{$path}5/yml/file2.yml", "{$path}5/expected.json"]
        ];
    }

    /**
     * @dataProvider gendiffPlainProvider
     */
    public function testGendiffPlain(string $path1, string $path2, string $expectedPath): void
    {
        $expected = trim(file_get_contents($expectedPath));
        $this->assertEquals($expected, trim(gendiff($path1, $path2, "plain")));
    }
    /**
    * @return array<array<string>>
    */
    public static function gendiffPlainProvider(): array
    {
        $path = self::PATH;
        return [["{$path}1/json/file1.json", "{$path}1/json/file2.json","{$path}1/expectedPlain"],
                ["{$path}1/yml/file1.yml", "{$path}1/yml/file2.yml", "{$path}1/expectedPlain"],
                ["{$path}2/json/file1.json", "{$path}2/json/file2.json", "{$path}2/expectedPlain"],
                ["{$path}2/yml/file1.yml", "{$path}2/yml/file2.yml", "{$path}2/expectedPlain"],
                ["{$path}3/json/file1.json", "{$path}3/json/file2.json", "{$path}3/expectedPlain"],
                ["{$path}3/yml/file1.yml", "{$path}3/yml/file2.yml", "{$path}3/expectedPlain"],
                ["{$path}4/json/file1.json", "{$path}4/json/file2.json", "{$path}4/expectedPlain"],
                ["{$path}4/yml/file1.yml", "{$path}4/yml/file2.yml", "{$path}4/expectedPlain"],
                ["{$path}5/json/file1.json", "{$path}5/json/file2.json", "{$path}5/expectedPlain"],
                ["{$path}5/yml/file1.yml", "{$path}5/yml/file2.yml","{$path}5/expectedPlain"]
        ];
    }

    /**
     * @dataProvider gendiffJsonProvider
     */
    public function testGendiffJson(string $path1, string $path2, string $expectedPath): void
    {
        $this->assertJsonStringEqualsJsonFile($expectedPath, trim(gendiff($path1, $path2, "json")));
    }
    /**
    * @return array<array<string>>
    */
    public static function gendiffJsonProvider(): array
    {
        $path = self::PATH;
        return [
            ["{$path}1/json/file1.json", "{$path}1/json/file2.json", "{$path}1/expectedJson.json"],
            ["{$path}1/yml/file1.yml", "{$path}1/yml/file2.yml", "{$path}1/expectedJson.json"],
                ["{$path}2/json/file1.json", "{$path}2/json/file2.json", "{$path}2/expectedJson.json"],
                ["{$path}2/yml/file1.yml", "{$path}2/yml/file2.yml", "{$path}2/expectedJson.json"],
                ["{$path}3/json/file1.json", "{$path}3/json/file2.json", "{$path}3/expectedJson.json"],
                ["{$path}3/yml/file1.yml", "{$path}3/yml/file2.yml", "{$path}3/expectedJson.json"],
                ["{$path}4/json/file1.json", "{$path}4/json/file2.json", "{$path}4/expectedJson.json"],
                ["{$path}4/yml/file1.yml", "{$path}4/yml/file2.yml", "{$path}4/expectedJson.json"],
                ["{$path}5/json/file1.json", "{$path}5/json/file2.json", "{$path}5/expectedJson.json"],
                ["{$path}5/yml/file1.yml", "{$path}5/yml/file2.yml","{$path}5/expectedJson.json"]
        ];
    }
}
