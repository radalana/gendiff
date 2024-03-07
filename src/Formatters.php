<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\format as toPlain;
use function Differ\Formatters\Stylish\format as toStylish;
use function Differ\Formatters\Json\format as toJson;

/**
 * @param string $format
 * @param array<int|string, mixed> $ast
 * @return string
 * @throws \Exception
 */
function format(string $format, array $ast): string
{
    switch ($format) {
        case 'plain':
            return toPlain($ast);
        case 'json':
            return toJson($ast);
        case 'stylish':
            return toStylish($ast);
        default:
            throw new \Exception("Undefined formatter");
    }
}
