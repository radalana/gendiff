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
    if ($format === 'plain') {
        return toPlain(($ast));
    }
    if ($format === 'json') {
        return toJson($ast);
    }

    return style($ast);
}
