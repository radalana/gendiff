<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

function trim(string $input): string
{
    return preg_replace('/[\s\n]+$/', '', $input);
}

class DifferTest extends TestCase
{
    public function testGendiffJson1(): void
    {
        $jsonFile1 = './tests/fixtures/test1/json/file1.json';
        $jsonFile2 = './tests/fixtures/test1/json/file2.json';
        //stylish
        $actualDefaultStylish = trim(gendiff($jsonFile1, $jsonFile2));
        $expected =  trim(file_get_contents('./tests/fixtures/test1/expected'));
        $this->assertEquals($expected, $actualDefaultStylish);

        $actual = trim(gendiff($jsonFile1, $jsonFile2, 'stylish'));
        $this->assertEquals($expected, $actual);

        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test1/expectedPlain.txt'));
        $this->assertEquals($expectedPlain, trim(gendiff($jsonFile1, $jsonFile2, 'plain')));

        //json
        $expectedJsonFile = './tests/fixtures/test1/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($jsonFile1, $jsonFile2, 'json'));
    }

    public function testGendiffYml1(): void
    {
        $ymlFile1 = './tests/fixtures/test1/yml/file1.yml';
        $ymlFile2 = './tests/fixtures/test1/yml/file2.yml';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test1/expected'));
        $this->assertEquals($expected, trim(gendiff($ymlFile1, $ymlFile2)));
        $this->assertEquals($expected, trim(gendiff($ymlFile1, $ymlFile2, 'stylish')));

        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test1/expectedPlain.txt'));
        $this->assertEquals($expectedPlain, trim(gendiff($ymlFile1, $ymlFile2, 'plain')));

        //json
        $expectedJsonFile = './tests/fixtures/test1/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($ymlFile1, $ymlFile2, 'json'));
    }

    /**Test data with list as a value */
    public function testGendiffJson2(): void
    {
        $jsonFile1 = './tests/fixtures/test2/json/file1.json';
        $jsonFile2 = './tests/fixtures/test2/json/file2.json';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test2/expected.json'));
        $this->assertEquals($expected, trim(gendiff($jsonFile1, $jsonFile2)));
        $this->assertEquals($expected, trim(gendiff($jsonFile1, $jsonFile2, 'stylish')));

         //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test2/expectedPlain'));
        $this->assertEquals($expectedPlain, trim(gendiff($jsonFile1, $jsonFile2, 'plain')));
        //json
        $expectedJsonFile = './tests/fixtures/test2/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($jsonFile1, $jsonFile2, 'json'));
    }

    public function testGendiffYml2(): void
    {
        $ymlFile1 = './tests/fixtures/test2/yml/file1.yml';
        $ymlFile2 = './tests/fixtures/test2/yml/file2.yml';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test2/expected.json'));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2, 'stylish'));
        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test2/expectedPlain'));
        $this->assertEquals($expectedPlain, gendiff($ymlFile1, $ymlFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test2/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($ymlFile1, $ymlFile2, 'json'));
    }

    public function testGendiffJson3(): void
    {
        $jsonFile1 = './tests/fixtures/test3/json/file1.json';
        $jsonFile2 = './tests/fixtures/test3/json/file2.json';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test3/expected.json'));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2, 'stylish'));
        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test3/expectedPlain'));
        $this->assertEquals($expectedPlain, gendiff($jsonFile1, $jsonFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test3/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($jsonFile1, $jsonFile2, 'json'));
    }

    public function testGendiffYml3(): void
    {
        $ymlFile1 = './tests/fixtures/test3/yml/file1.yml';
        $ymlFile2 = './tests/fixtures/test3/yml/file2.yml';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test3/expected.json'));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2, 'stylish'));
        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test3/expectedPlain'));
        $this->assertEquals($expectedPlain, gendiff($ymlFile1, $ymlFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test3/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($ymlFile1, $ymlFile2, 'json'));
    }

    /** Test data with a list as a part of complex value */
    public function testGendiffJson4(): void
    {
        $jsonFile1 = './tests/fixtures/test4/json/file1.json';
        $jsonFile2 = './tests/fixtures/test4/json/file2.json';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test4/expected.json'));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2), 'stylish');

        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test4/expectedPlain'));
        $this->assertEquals($expectedPlain, gendiff($jsonFile1, $jsonFile2, 'plain'));

        //json
        $expectedJsonFile = './tests/fixtures/test4/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($jsonFile1, $jsonFile2, 'json'));
    }

    public function testGendiffYml4(): void
    {
        $ymlFile1 = './tests/fixtures/test4/yml/file1.yml';
        $ymlFile2 = './tests/fixtures/test4/yml/file2.yml';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test4/expected.json'));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2, 'stylish'));
        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test4/expectedPlain'));
        $this->assertEquals($expectedPlain, gendiff($ymlFile1, $ymlFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test4/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($ymlFile1, $ymlFile2, 'json'));
    }

    public function testGendiffJson5(): void
    {
        $jsonFile1 = './tests/fixtures/test5/json/file1.json';
        $jsonFile2 = './tests/fixtures/test5/json/file2.json';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test5/expected.json'));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2), 'stylish');

        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test5/expectedPlain'));
        $this->assertEquals($expectedPlain, gendiff($jsonFile1, $jsonFile2, 'plain'));

        //json
        $expectedJsonFile = './tests/fixtures/test5/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($jsonFile1, $jsonFile2, 'json'));
    }

    public function testGendiffYml5(): void
    {
        $ymlFile1 = './tests/fixtures/test5/yml/file1.yml';
        $ymlFile2 = './tests/fixtures/test5/yml/file2.yml';

        //stylish
        $expected = trim(file_get_contents('./tests/fixtures/test5/expected.json'));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2, 'stylish'));
        //plain text
        $expectedPlain = trim(file_get_contents('./tests/fixtures/test5/expectedPlain'));
        $this->assertEquals($expectedPlain, gendiff($ymlFile1, $ymlFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test5/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($ymlFile1, $ymlFile2, 'json'));
    }
}
