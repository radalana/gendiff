<?php

namespace Code\Formatters;

use function Code\Formatters\Plain\toPlain;
use function Code\Formatters\Stylish\style;

function format(string $format, array $ast)
{
    if ($format === 'plain'){
        return toPlain(($ast));
    }
    
    return style($ast);
}