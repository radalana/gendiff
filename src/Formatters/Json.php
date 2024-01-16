<?php

namespace Code\Formatters\Json;

use function Code\Formatters\Stylish\addSign;

function toJson(array $ast)
{
    return json_encode(array_merge(...addSign($ast)));
}
