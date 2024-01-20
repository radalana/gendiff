<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;
use Exception;
use stdClass;

function readFromFile(string $pathTofile): string
{
    $file = fopen($pathTofile, "r");
    if ($file === false) {
        throw new Exception("Failed to open file!");
    }

    $fileSize = filesize($pathTofile);
    if ($fileSize === 0) {
        throw new Exception("{$pathTofile} is empty!");
    }
    $data = fread($file, $fileSize);
    if ($data === false) {
        throw new Exception("Failed to read file!");
    }

    if (fclose($file) === false) {
        throw new Exception("Failed to close file!");
    };
    return $data;
}

function getData(string $pathToFile): stdClass
{
    $dumped = readFromFile($pathToFile);//строковое представление
    $pathExtension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    if ($pathExtension === 'json') {
        return json_decode($dumped);
    }
    if (($pathExtension === 'yml') || ($pathExtension === 'yaml')) {
        return Yaml::parse($dumped, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    switch ($pathExtension) {
        case 'json':
            return json_decode($dumped);
        case 'yml':
            return Yaml::parse($dumped, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'yaml':
            return Yaml::parse($dumped, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new Exception('The file format is Invalid!');
    }
}
