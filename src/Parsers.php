<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;
use stdClass;
use Exception;

function parse(string $dumped, string $type): stdClass
{
    switch ($type) {
        case 'json':
            return json_decode($dumped);
        case 'yml':
            return Yaml::parse($dumped, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'yaml':
            return Yaml::parse($dumped, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new Exception('The type is Invalid!');
    }
}
