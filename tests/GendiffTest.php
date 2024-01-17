<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Code\Gendiff\gendiff;

class GendiffTest extends TestCase
{
    public function testGendiffJson1()
    {
        $jsonFile1 = './tests/fixtures/test1/json/file1.json';
        $jsonFile2 = './tests/fixtures/test1/json/file2.json';
        $expected = file_get_contents('./tests/fixtures/test1/expected.json');
        //stylish
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2, 'stylish'));
        //plain text
        $expectedPlain = file_get_contents('./tests/fixtures/test1/expectedPlain');
        $this->assertEquals($expectedPlain, gendiff($jsonFile1, $jsonFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test1/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($jsonFile1, $jsonFile2, 'json'));
    }
    public function testGendiffYml1()
    {
        $ymlFile1 = './tests/fixtures/test1/yml/file1.yml';
        $ymlFile2 = './tests/fixtures/test1/yml/file2.yml';
        $expected = file_get_contents('./tests/fixtures/test1/expected.json');
        //stylish
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2, 'stylish'));
        //plain text
        $expectedPlain = file_get_contents('./tests/fixtures/test1/expectedPlain');
        $this->assertEquals($expectedPlain, gendiff($ymlFile1, $ymlFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test1/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($ymlFile1, $ymlFile2, 'json'));
    }

    /**Test data with list as a value */
    public function testGendiffJson2()
    {
        $jsonFile1 = './tests/fixtures/test2/json/file1.json';
        $jsonFile2 = './tests/fixtures/test2/json/file2.json';
        $expected = file_get_contents('./tests/fixtures/test2/expected.json');
        //stylish
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2, 'stylish'));
         //plain text
        $expectedPlain = file_get_contents('./tests/fixtures/test2/expectedPlain');
        $this->assertEquals($expectedPlain, gendiff($jsonFile1, $jsonFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test2/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($jsonFile1, $jsonFile2, 'json'));
    }

    public function testGendiffYml2()
    {
        $ymlFile1 = './tests/fixtures/test2/yml/file1.yml';
        $ymlFile2 = './tests/fixtures/test2/yml/file2.yml';
        $expected = file_get_contents('./tests/fixtures/test2/expected.json');
        //stylish
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2, 'stylish'));
        //plain text
        $expectedPlain = file_get_contents('./tests/fixtures/test2/expectedPlain');
        $this->assertEquals($expectedPlain, gendiff($ymlFile1, $ymlFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test2/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($ymlFile1, $ymlFile2, 'json'));
    }

    public function testGendiffJson3()
    {
        $jsonFile1 = './tests/fixtures/test3/json/file1.json';
        $jsonFile2 = './tests/fixtures/test3/json/file2.json';
        $expected = file_get_contents('./tests/fixtures/test3/expected.json');
        //stylish
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2, 'stylish'));
        //plain text
        $expectedPlain = file_get_contents('./tests/fixtures/test3/expectedPlain');
        $this->assertEquals($expectedPlain, gendiff($jsonFile1, $jsonFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test3/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($jsonFile1, $jsonFile2, 'json'));
    }

    public function testGendiffYml3()
    {
        $ymlFile1 = './tests/fixtures/test3/yml/file1.yml';
        $ymlFile2 = './tests/fixtures/test3/yml/file2.yml';
        $expected = file_get_contents('./tests/fixtures/test3/expected.json');
        //stylish
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2, 'stylish'));
        //plain text
        $expectedPlain = file_get_contents('./tests/fixtures/test3/expectedPlain');
        $this->assertEquals($expectedPlain, gendiff($ymlFile1, $ymlFile2, 'plain'));
        //json
        $expectedJsonFile = './tests/fixtures/test3/expectedJson.json';
        $this->assertJsonStringEqualsJsonFile($expectedJsonFile, gendiff($ymlFile1, $ymlFile2, 'json'));
    }

    /** Test data with a list as a part of complex value */
    public function testGendiffJson4()
    {
        $jsonFile1 = './tests/fixtures/test4/json/file1.json';
        $jsonFile2 = './tests/fixtures/test4/json/file2.json';
        $expected = file_get_contents('./tests/fixtures/test4/expected.json');
        //stylish
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2), 'stylish');
    }
}
