<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

class DifferTest extends TestCase
{
    private const PATH = './tests/fixtures/test';
    private const PATH2 = './tests/fixtures/';
    private const EXPECTED_STYLISH = 'expected.json';

    /**
    * @return array<array<string>>
    */
    public static function gendiffProvider(): array
    {
        return [
            ['test1', 'json'],
            ['test1', 'yml'],

            ['test2', 'json'],
            ['test2', 'yml'],

            ['test3', 'json'],
            ['test3', 'yml'],

            ['test4', 'json'],
            ['test4', 'yml'],

            ['test5', 'json'],
            ['test5', 'yml'],
        ];
    }
    private function getFixturePath(int $testNumber, string $fixtureName): string
    {
           $directory = self::PATH;
           $subDir = pathinfo($fixtureName, PATHINFO_EXTENSION);
           $expected = 'expected';

        if (str_contains($fixtureName, $expected)) {
            return "{$directory}{$testNumber}/{$fixtureName}";
        }
            return "{$directory}{$testNumber}/{$subDir}/{$fixtureName}";
    }

    private function getFixturePath2(string $fixtureName): string
    {
        $directory = self::PATH2;
        return "{$directory}{$fixtureName}/";
    }

    /**
     * @dataProvider gendiffStylishProvider
     */
    public function testGendiffStylish(string $testDirectory, string $format): void
    {
        $path1 = "{$this->getFixturePath2($testDirectory)}{$format}/file1.{$format}";
        $path2 = "{$this->getFixturePath2($testDirectory)}{$format}/file2.{$format}";

        $expectedFile = $this->getFixturePath2($testDirectory) . self::EXPECTED_STYLISH;

        $result = gendiff($path1, $path2, "stylish");
        $expected = file_get_contents($expectedFile);
        $this->assertEquals($expected, $result);

        $resultDefault = gendiff($path1, $path2);
        $this->assertEquals($expected, $resultDefault);
    }
    /**
    * @return array<array<string>>
    */
    public static function gendiffStylishProvider(): array
    {
        return [
            ['test1', 'json'],
            ['test1', 'yml'],

            ['test2', 'json'],
            ['test2', 'yml'],

            ['test3', 'json'],
            ['test3', 'yml'],

            ['test4', 'json'],
            ['test4', 'yml'],

            ['test5', 'json'],
            ['test5', 'yml'],
        ];
    }
    /**
     * @dataProvider gendiffPlainProvider
     */

    public function testGendiffPlain(int $testNumber, string $file1, string $file2, string $expectedFile): void
    {
        $path1 = $this->getFixturePath($testNumber, $file1);
        $path2 = $this->getFixturePath($testNumber, $file2);
        $expectedPath = $this->getFixturePath($testNumber, $expectedFile);

        $expected = file_get_contents($expectedPath);
        $this->assertEquals($expected, gendiff($path1, $path2, "plain"));
    }
    /**
    * @return array<array<string>>
    */
    public static function gendiffPlainProvider(): array
    {
        return [[1, "file1.json", "file2.json","expectedPlain"],
                [1, "file1.yml", "file2.yml", "expectedPlain"],
                [2, "file1.json", "file2.json", "/expectedPlain"],
                [2, "file1.yml", "file2.yml", "expectedPlain"],
                [3, "file1.json", "file2.json", "expectedPlain"],
                [3, "file1.yml", "file2.yml", "expectedPlain"],
                [4, "file1.json", "file2.json", "expectedPlain"],
                [4, "file1.yml", "file2.yml", "expectedPlain"],
                [5, "file1.json", "file2.json", "expectedPlain"],
                [5, "file1.yml", "file2.yml","expectedPlain"]
        ];
    }

    /**
     * @dataProvider gendiffJsonProvider
     */
    public function testGendiffJson(int $testNumber, string $file1, string $file2, string $expectedFile): void
    {
        $expectedPath = $this->getFixturePath($testNumber, $expectedFile);
        $path1 = $this->getFixturePath($testNumber, $file1);
        $path2 = $this->getFixturePath($testNumber, $file2);
        $this->assertJsonStringEqualsJsonFile($expectedPath, gendiff($path1, $path2, "json"));
    }
    /**
    * @return array<array<string>>
    */
    public static function gendiffJsonProvider(): array
    {
        return [[1, "file1.json", "file2.json", "expectedJson.json"],
                [1, "file1.yml", "file2.yml", "expectedJson.json"],
                [2, "file1.json", "file2.json", "expectedJson.json"],
                [2, "file1.yml", "file2.yml", "expectedJson.json"],
                [3, "file1.json", "file2.json", "expectedJson.json"],
                [3, "file1.yml", "file2.yml", "expectedJson.json"],
                [4, "file1.json", "file2.json", "expectedJson.json"],
                [4, "file1.yml", "file2.yml", "expectedJson.json"],
                [5, "file1.json", "file2.json", "expectedJson.json"],
                [5, "file1.yml", "file2.yml","expectedJson.json"]
        ];
    }
}
