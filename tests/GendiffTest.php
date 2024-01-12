<?php

namespace Code\Phpunit\Tests;

use PHPUnit\Framework\TestCase;


use function Code\Gendiff\gendiff;


class GendiffTest extends TestCase {

    /*
    public function testInternalRepresentation(): void
    {
        $jsonFile1 = './tests/fixtures/jsonFiles/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/file2.json';
        $expected = [
            ['key' => 'group3', 'status' => 'added', 'value' => ['deep' => ['id' => ['number' => 45]], 'fee' => 100500]],
            ['key' => 'common', 'children' => [
                ['key' => 'follow', 'status' => 'added', 'value' => 'false'],
                ['key' => 'setting4', 'status' => 'added', 'value' => 'blah blah'],
                ['key' => 'setting5', 'status' => 'added', 'value' => ['key5' => 'value5']],
                ['key' => 'setting1', 'value' => 'Value 1'],
            
                ['key' => 'setting3', 'status' => ['oldValue' => 'true', 'newValue' => 'NULL']],
            
            
            
                ['key' => 'setting6', 'children' => [
                    ['key' => 'ops', 'status' => 'added', 'value' => 'vops'],
                    ['key' => 'key', 'value' => 'value'],
                    ['key' => 'doge', 'children' => [
                        ['key' => 'wow', 'status' => ['oldValue' => '', 'newValue' => 'so much']]
                        ]
                    ]]
                ],


                ['key' => 'setting2', 'value' => 200, 'status' => 'deleted']
        
            ]],
            
                ['key' => 'group1', 'children' => [
                    ['key' => 'baz', 'status' => ['oldValue' => 'bas', 'newValue' => 'bars']],
                    ['key' => 'foo', 'value' => 'bar'],
                    ['key' => 'nest', 'status' => ['oldValue' => ['key' => 'value'], 'newValue' => 'str']]
                ]],
                ['key' => 'group2', 'status' => 'deleted', 'value' => ['abc' => 12345, 'deep' => ['id' => 45]]]
            
                ];
            $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
    }
    */
    public function testGendiffJson1()
    {
        $jsonFile1 = './tests/fixtures/jsonFiles/test1/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/test1/file2.json';
        $expected = file_get_contents('./tests/fixtures/expected.json');
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
    }
    //вложенный массив где одним из измененных значений является индексированный массив (список)
    public function testGendiffJson2()
    {
        $jsonFile1 = './tests/fixtures/jsonFiles/test2/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/test2/file2.json';
        $expected = file_get_contents('./tests/fixtures/jsonFiles/test2/expected.json');
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));

    }

    public function testGendiffJson3()
    {
        $jsonFile1 = './tests/fixtures/jsonFiles/test3/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/test3/file2.json';
        $expected = file_get_contents('./tests/fixtures/jsonFiles/test3/expected.json');
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
    }

    public function testGendiffYml()
    {
        $ymlFile1 = './tests/fixtures/ymlFiles/file1.yml';
        $ymlFile2 = './tests/fixtures/ymlFiles/file2.yml';
        $expected = file_get_contents('./tests/fixtures/expected.json');
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
    }


    /*
    public function testGendiffPlainJson(): void {
        $jsonFile1 = './tests/fixtures/jsonFiles/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/file2.json';
        $expected = file_get_contents('./tests/fixtures/expected.json');
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
    }
    public function testGendiffYml()
    {
        $jsonFile1 = './tests/fixtures/ymlFiles/file1.json';
        $jsonFile2 = './tests/fixtures/ymlFiles/file2.json';
        $expected = file_get_contents('./tests/fixtures/expected.json');
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
    }
    public function testGendiff(): void
    {
        $expected = "{\n- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22\n- timeout: 50\n+ timeout: 20\n+ verbose: true\n}\n";

        $jsonFile1 = './tests/fixtures/jsonFiles/test1/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/test1/file2.json';
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));

        $ymlFile1 = './tests/fixtures/ymlFiles/test1/file1.yml';
        $ymlFile2 = './tests/fixtures/ymlFiles/test1/file2.yml';
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
    }

    public function testGendiff2(): void
    {
        $expected = "{\n+ api-key: 550e\n+ api-version: v1\n- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22\n- timeout: 50\n+ timeout: 20\n+ verbose: true\n}\n";
        
        $jsonFile1 = './tests/fixtures/jsonFiles/test2/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/test2/file2.json';
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        
        $ymlFile1 = './tests/fixtures/ymlFiles/test2/file1.yml';
        $ymlFile2 = './tests/fixtures/ymlFiles/test2/file2.yml';
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
    }

    //**same keys, all values changed*/
    /*
    public function testGendiff3(): void
    {
        $expected = "{\n- api-key: 550e\n+ api-key: 770k\n- api-version: v1\n+ api-version: v2\n- host: hexlet.io\n+ host: www.example.com\n- timeout: 20\n+ timeout: 50\n- verbose: true\n+ verbose: false\n}\n";

        $jsonFile1 = './tests/fixtures/jsonFiles/test3/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/test3/file2.json';
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        
        $ymlFile1 = './tests/fixtures/ymlFiles/test3/file1.yml';
        $ymlFile2 = './tests/fixtures/ymlFiles/test3/file2.yml';
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        
        
    }

    /** First file is empty*/
    /*
    public function testEmptyFile(): void
    {
        $jsonFile1 = './tests/fixtures/jsonFiles/test4/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/test4/file2.json';
        $this->expectExceptionMessage("{$jsonFile1} is empty!");
        gendiff($jsonFile1, $jsonFile2);
        
        $ymlFile1 = './tests/fixtures/ymlFiles/test4/file1.yml';
        $ymlFile2 = './tests/fixtures/ymlFiles/test4/file2.yml';
        $this->expectExceptionMessage("{$ymlFile1} is empty!");
        gendiff($ymlFile1, $ymlFile2);
        
    }

    public function testIdenticalFiles(): void
    {
        $expected = "{\n  host: hexlet.io\n  timeout: 20\n  verbose: true\n}\n";

        $jsonFile1 = './tests/fixtures/jsonFiles/test5/file1.json';
        $jsonFile2 = './tests/fixtures/jsonFiles/test5/file2.json';
        $this->assertEquals($expected, gendiff($jsonFile1, $jsonFile2));
        
        $ymlFile1 = './tests/fixtures/ymlFiles/test5/file1.yml';
        $ymlFile2 = './tests/fixtures/ymlFiles/test5/file2.yml';
        $this->assertEquals($expected, gendiff($ymlFile1, $ymlFile2));
        
    }
    */
    
}
