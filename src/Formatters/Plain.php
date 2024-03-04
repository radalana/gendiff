<?php

namespace Differ\Formatters\Plain;

use function Differ\Differ\getChildren;
use function Differ\Differ\isIndexedArray;
use function Differ\Differ\toString;
use function Differ\Differ\getValue;

function isComplex(mixed $value): bool
{
    return (is_object($value)  || isIndexedArray($value));
}

//In plain text the data of the string type is enclosed in quotation marks.

function formatString(mixed $value): mixed
{
    if (isComplex($value)) {
        return '[complex value]';
    }
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if (is_null($value)) {
        return 'null';
    }
    if (is_numeric($value)) {
        return $value;
    }
    return "'{$value}'";
}

/**
 * @param array<int|string, mixed> $data
 * @return string
 */
function format(array $data): string
{
    $iter = function ($data, $ancestry) use (&$iter) {
        $name = $data['key'];
        $newAncestry = ($ancestry === '') ? "{$name}" : "{$ancestry}.{$name}";
        $differ = $data['differ'];
        $value = getValue($data);
        switch ($differ) {
            case 'added':
                $strValue = formatString($value);
                return "Property '{$newAncestry}' was added with value: {$strValue}";
            case 'deleted':
                return "Property '{$newAncestry}' was removed";
            case 'changed':
                $val1 = getValue($data, 'old');
                    $val2 = getValue($data, 'new');
                    $valStr1 = formatString($val1);
                    $valStr2 = formatString($val2);
                return "Property '{$newAncestry}' was updated. From {$valStr1} to {$valStr2}";
            case 'unchanged':
                return;
            case 'nested':
                $children = getChildren($data);
                $newChildren = array_filter(array_map(fn($child) => $iter($child, $newAncestry), $children));
                return implode("\n", $newChildren);
            default:
                throw new \Exception('Not valid differ of node!');
        }
    };
    return implode("\n", array_map(fn($value) => $iter($value, ''), $data));
}
