<?php

namespace Differ\Formatters\Json;

use function Differ\Formatters\Stylish\addSign;

function toJson(array $ast)
{
    return json_encode(array_merge(...addSign($ast)));
}
