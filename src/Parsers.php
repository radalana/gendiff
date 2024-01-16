<?php

namespace Code\Parsers;

use Symfony\Component\Yaml\Yaml;
use Exception;

function readFromFile(string $pathTofile)
{
    $file = fopen($pathTofile, "r") or die("Unable to open file!");
    $fileSize = filesize($pathTofile);
    if (!$fileSize) {
        throw new Exception("{$pathTofile} is empty!");
    }
    $data = fread($file, $fileSize);
    fclose($file);
    return $data;
}

function getData(string $pathToFile)//object или array?
{
    $dumped = readFromFile($pathToFile);//строковое представление
    $pathExtension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    if ($pathExtension === 'json') {
        return json_decode($dumped);
    }
    if (($pathExtension === 'yml') || ($pathExtension === 'yaml')) {
        return Yaml::parse($dumped, Yaml::PARSE_OBJECT_FOR_MAP);
        #return Yaml::parseFile($pathToFile);
    }
    return [];
}
