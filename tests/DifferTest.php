<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

class DifferTest extends TestCase
{
    private const PATH = './tests/fixtures/';
    private const EXPECTED_STYLISH = 'expected.json';
    private const EXPECTED_PLAIN = 'expectedPlain';
    private const EXPECTED_JSON = 'expectedJson.json';

    private function getFixturePath(string $fixtureName): string
    {
        $directory = self::PATH;
        return "{$directory}{$fixtureName}/";
    }

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

    /**
     * @dataProvider gendiffProvider
     */
    public function testGendiffStylish(string $testDirectory, string $format): void
    {
        $path1 = "{$this->getFixturePath($testDirectory)}{$format}/file1.{$format}";
        $path2 = "{$this->getFixturePath($testDirectory)}{$format}/file2.{$format}";
        $expectedFile = $this->getFixturePath($testDirectory) . self::EXPECTED_STYLISH;

        $result = gendiff($path1, $path2, "stylish");
        $expected = file_get_contents($expectedFile);
        $this->assertEquals($expected, $result);

        $resultDefault = gendiff($path1, $path2);
        $this->assertEquals($expected, $resultDefault);
    }

    /**
     * @dataProvider gendiffProvider
     */
    public function testGendiffPlain(string $testDirectory, string $format): void
    {
        $path1 = "{$this->getFixturePath($testDirectory)}{$format}/file1.{$format}";
        $path2 = "{$this->getFixturePath($testDirectory)}{$format}/file2.{$format}";
        $expectedFile = $this->getFixturePath($testDirectory) . self::EXPECTED_PLAIN;

        $expected = file_get_contents($expectedFile);
        $this->assertEquals($expected, gendiff($path1, $path2, "plain"));
    }

    /**
     * @dataProvider gendiffProvider
     */
    public function testGendiffJson(string $testDirectory, string $format): void
    {
        $path1 = "{$this->getFixturePath($testDirectory)}{$format}/file1.{$format}";
        $path2 = "{$this->getFixturePath($testDirectory)}{$format}/file2.{$format}";
        $expectedPath = $this->getFixturePath($testDirectory) . self::EXPECTED_JSON;

        $this->assertJsonStringEqualsJsonFile($expectedPath, gendiff($path1, $path2, "json"));
    }
}
