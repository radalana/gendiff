<?php

namespace Differ\Formatters\Json;

use function Differ\Formatters\Stylish\addSign;

/**
 * @param array<int|string, mixed> $ast
 * @return string|false
 */
function format(array $ast): string|false
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
