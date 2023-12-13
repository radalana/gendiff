<?php

use Symfony\Component\Yaml\Yaml;

namespace Code\Parsers;

function getData(string $pathTofile): array
{
    $pathExtension = pathinfo($pathTofile, PATHINFO_EXTENSION);
    $data = [];
    switch ($pathExtension) {
        case 'json':
            return #json_decode()
        case 'yml'
            return
        case 'yaml'
            return
        default:##    
    }
}