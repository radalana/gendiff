<?php

namespace Differ\Formatters\Json;

use function Differ\Formatters\Stylish\addSign;

/**
 * @param array<string, mixed> $ast
 * @return string|false
 */
function toJson(array $ast): string|false
{
    return json_encode(array_merge(...addSign($ast)));
}
