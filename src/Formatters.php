<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\toPlain;
use function Differ\Formatters\Stylish\style;
use function Differ\Formatters\Json\toJson;

/**
 * @param string $format
 * @param array<int|string, mixed> $ast
 * @return string
 */
function format(string $format, array $ast): string
{
    switch ($format) {
        case 'plain':
            return toPlain($ast);
        case 'json':
            return toJson($ast);
        case 'stylish':
            return style($ast);
        default:
            throw new \Exception("Undefinde formatter");
    }
}
