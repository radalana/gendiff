<?php

namespace Code\Formatters\Plain;

use function Code\Formatters\format;
use function Code\Formatters\Stylish\getChildren;
use function Code\Gendiff\hasChildren;
use function Code\Formatters\Stylish\isChanged;
use function Code\Formatters\Stylish\toString;

function formatString($value)
{
    if (($value === 'true') || ($value === 'null') || ($value === 'false') || (!is_string($value)) || ($value === '[complex value]')) {
        return $value;
    }

    return "'{$value}'";
}

function toPlain(array $data)
{
    #print_r($data);
    $iter = function ($data, $ancestry) use (&$iter) {
        $name = $data['key'];
        #var_dump($ancestry);
        $newAncestry = (empty($ancestry)) ? "{$name}" : "{$ancestry}.{$name}";
        #var_dump($newAncestry);
        if (!hasChildren($data)) {
            #var_dump($data);
            if (isChanged($data)) {
                if ($data['status'] === 'added') {
                    $value = is_object($data['value']) ? '[complex value]' : formatString(toString($data['value']));
                    return "Property '{$newAncestry}' was added with value: {$value}";
                } elseif ($data['status'] === 'deleted') {
                    return "Property '{$newAncestry}' was removed";
                } else {
                    $oldValue = is_object($data['value']['oldValue']) ? "[complex value]" : formatString(toString($data['value']['oldValue']));
                    $newValue = is_object($data['value']['newValue']) ? "[complex value]" : formatString(toString($data['value']['newValue']));
                    return "Property '{$newAncestry}' was updated. From {$oldValue} to {$newValue}";
                }
            }
            return;
        }

        $children = getChildren($data);
        #var_dump($children);
        #var_dump($newAncestry);
        $newChildren = array_filter(array_map(fn($child) => $iter($child, $newAncestry), $children));
        #var_dump($newChildren);
        return implode("\n", $newChildren);
    };
    return implode("\n", array_map(fn($value) => $iter($value, ''), $data)) . "\n";
}
