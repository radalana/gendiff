<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\toPlain;
use function Differ\Formatters\Stylish\style;
use function Differ\Formatters\Json\toJson;

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
