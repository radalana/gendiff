<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

class DifferTest extends TestCase
{
    private const PATH = './tests/fixtures/test';
    private function getFixturePath(int $testNumber, string $fixtureName)
    {
           $directory = self::PATH;
           $subDir = pathinfo($fixtureName, PATHINFO_EXTENSION);
           $expected = 'expected';

           if (str_contains($fixtureName, $expected)) {
            return "{$directory}{$testNumber}/{$fixtureName}";
           }
            return "{$directory}{$testNumber}/{$subDir}/{$fixtureName}";
    }
    /**
     * @dataProvider gendiffStylishProvider
     */
    public function testGendiffStylish(int $testNumber, string $file1, string $file2, string $expectedFile): void
    {
        $path1 = $this->getFixturePath($testNumber, $file1);
        $path2 = $this->getFixturePath($testNumber, $file2);
        $expectedFile = $this->getFixturePath($testNumber, $expectedFile);

        $result = gendiff($path1, $path2, "stylish");
        $expected =  trim(file_get_contents($expectedFile));
        $this->assertEquals($expected, $result);

        $resultDefault = gendiff($path1, $path2);
        $this->assertEquals($expected, $resultDefault);
    }
    /**
    * @return array<array<string>>
    */
    public static function gendiffStylishProvider(): array
    {
        return [[1, "file1.json", "file2.json", "expected.json"],
                [1, "file1.yml", "file2.yml", "expected.json"],
                [2, "file1.json", "file2.json", "expected.json"],
                [2, "file1.yml", "file2.yml", "expected.json"],
                [3, "file1.json", "file2.json", "expected.json"],
                [3, "file1.yml", "file2.yml", "expected.json"],
                [4, "file1.json", "file2.json", "expected.json"],
                [4, "file1.yml", "file2.yml", "expected.json"],
                [5, "file1.json", "file2.json", "expected.json"],
                [5, "file1.yml", "file2.yml", "expected.json"]
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

        $expected = trim(file_get_contents($expectedPath));
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
