<?php

namespace Differ\Formatters\Json;
/**
 * @param array<int|string, mixed> $ast
 * @return string|false
 */
function format(array $ast): string|false
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
