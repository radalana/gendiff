<?php

namespace Code\Formatters\Plain;

use function Code\Formatters\Stylish\getChildren;
use function Code\Gendiff\hasChildren;
use function Code\Formatters\Stylish\isChanged;
use function Code\Formatters\Stylish\isIndexedArray;
use function Code\Formatters\Stylish\toString;
use function Code\Gendiff\getValue;

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

function toPlain(array $data): string
{
    $iter = function ($data, $ancestry) use (&$iter) {
        $name = $data['key'];
        $newAncestry = (empty($ancestry)) ? "{$name}" : "{$ancestry}.{$name}";
        if (!hasChildren($data)) {
            if (isChanged($data)) {
                $value = getValue($data);
                if ($data['status'] === 'added') {
                    $strValue = (is_object($value) || isIndexedArray($value)) ?
                    '[complex value]' : formatString(toString($value));
                    return "Property '{$newAncestry}' was added with value: {$strValue}";
                } elseif ($data['status'] === 'deleted') {
                    return "Property '{$newAncestry}' was removed";
                } else {
                    $oldVal = getValue($data, 'old');
                    $newVal = getValue($data, 'new');
                    $oldValueStr = (is_object($oldVal) || isIndexedArray($oldVal)) ?
                    "[complex value]" : formatString(toString($oldVal));
                    $newValueStr = (is_object($newVal) || isIndexedArray($newVal)) ?
                    "[complex value]" : formatString(toString($newVal));
                    return "Property '{$newAncestry}' was updated. From {$oldValueStr} to {$newValueStr}";
                }
            }
            return;
        }

        $children = getChildren($data);
        $newChildren = array_filter(array_map(fn($child) => $iter($child, $newAncestry), $children));
        return implode("\n", $newChildren);
    };
    return implode("\n", array_map(fn($value) => $iter($value, ''), $data)) . "\n";
}
