<?php

namespace Code\Formatters\Json;

use function Code\Formatters\Stylish\addSign;

function toJson($ast)
{
    return json_encode(array_merge(...addSign($ast)));
}