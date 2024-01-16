<?php

namespace Code\Formatters;

use function Code\Formatters\Plain\toPlain;
use function Code\Formatters\Stylish\style;
use function Code\Formatters\Json\toJson;

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
