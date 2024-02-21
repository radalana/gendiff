<?php

namespace Differ\Formatters\Plain;

use function Differ\Differ\getChildren;
use function Differ\Differ\hasChildren;
use function Differ\Differ\isChanged;
use function Differ\Differ\isIndexedArray;
use function Differ\Differ\toString;
use function Differ\Differ\getValue;

//In plain text the data of the string type is enclosed in quotation marks.
function formatString(mixed $value): mixed
{
    if (
        ($value === 'true') || ($value === 'null') || ($value === 'false')
        || (!is_string($value)) || ($value === '[complex value]')
    ) {
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
        if (!hasChildren($data)) {
            if (isChanged($data)) {
                $value = getValue($data);
                if ($data['differ'] === 'added') {
                    $strValue = (is_object($value) || isIndexedArray($value)) ?
                    '[complex value]' : formatString(toString($value));
                    return "Property '{$newAncestry}' was added with value: {$strValue}";
                } elseif ($data['differ'] === 'deleted') {
                    return "Property '{$newAncestry}' was removed";
                } else {
                    $firstFileVal = getValue($data, 'old');
                    $secondFileVal = getValue($data, 'new');
                    $firstFileValueStr = (is_object($firstFileVal) || isIndexedArray($firstFileVal)) ?
                    "[complex value]" : formatString(toString($firstFileVal));
                    $secondFileValueStr = (is_object($secondFileVal) || isIndexedArray($secondFileVal)) ?
                    "[complex value]" : formatString(toString($secondFileVal));
                    return "Property '{$newAncestry}' was updated. From {$firstFileValueStr} to {$secondFileValueStr}";
                }
            }
            return;
        };
        $children = getChildren($data);
        $newChildren = array_filter(array_map(fn($child) => $iter($child, $newAncestry), $children));
        return implode("\n", $newChildren);
    };
    return implode("\n", array_map(fn($value) => $iter($value, ''), $data));
}
