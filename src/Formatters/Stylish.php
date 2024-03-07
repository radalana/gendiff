<?php

namespace Differ\Formatters\Stylish;

use Exception;

use function Differ\Differ\getChildren;
use function Differ\Differ\isIndexedArray;
use function Differ\Differ\toString;

const SPACES_COUNT = 4;
const REPLACER = ' ';

function objectToArray(mixed $data): mixed
{
    //if data is a primitive data
    if (!is_object($data) && !is_array($data)) {
        return $data;
    }
    //if data is an array
    if (is_array($data)) {
        if (key_exists('value1', $data) && key_exists('value2', $data)) {
            return array_map(fn($value) => objectToArray($value), $data);
        }
        return $data;
    }
    $arrayOfProperties = get_object_vars($data);
    $result = array_map(fn($value) => objectToArray($value), $arrayOfProperties);
    return $result;
}

/**
 * @param array<string, mixed>|scalar $data
 * @param int $depth
 * @return string
 */
function stringify(mixed $data, int $depth): string
{
    if (!is_array($data)) {
        return toString($data);
    }
    $indentSize = $depth * SPACES_COUNT;
    $currentIndent = str_repeat(REPLACER, $indentSize);
    $bracketIndent = str_repeat(REPLACER, $indentSize - SPACES_COUNT);
    if (isIndexedArray($data)) { //для обвчного массива
        $string = implode(', ', $data);
        return "[{$string}]";
    }
    $lines = array_map(function ($key, $value) use ($currentIndent, &$depth) {
        $formattedValue = stringify($value, $depth + 1);
        return "{$currentIndent}{$key}: {$formattedValue}";
    }, array_keys($data), $data);
    $result = ['{', ...$lines, "{$bracketIndent}}"];
    return implode("\n", $result);
}

/**
 * @param array<int|string, mixed> $data
 * @return string
 */
function format(array $data): string
{
    $iter = function (mixed $currentData, int $depth) use (&$iter) {
        if (!is_array($currentData)) {
            return toString($currentData);
        }

        $indentSize = $depth * SPACES_COUNT;
        $currentIndent = str_repeat(REPLACER, $indentSize);
        $signIndent = str_repeat(REPLACER, ($indentSize - 2));
        $differ = $currentData['differ'];
        switch ($differ) {
            case 'added':
                $valAsArray = objectToArray($currentData['value']);
                $stringVal = stringify($valAsArray, $depth + 1);
                return "{$signIndent}+ {$currentData['key']}: {$stringVal}";
            case 'deleted':
                $valAsArray = objectToArray($currentData['value']);
                $stringVal = stringify($valAsArray, $depth + 1);
                return "{$signIndent}- {$currentData['key']}: {$stringVal}";
            case 'unchanged':
                $valAsArray = objectToArray($currentData['value']);
                $stringVal = stringify($valAsArray, $depth);
                return "{$currentIndent}{$currentData['key']}: {$stringVal}";
            case 'changed':
                $val1 = stringify(objectToArray($currentData['value1']), $depth + 1);
                $val2 = stringify(objectToArray($currentData['value2']), $depth + 1);
                $keyVal1 = "{$signIndent}- {$currentData['key']}: {$val1}";
                $keyVal2 = "{$signIndent}+ {$currentData['key']}: {$val2}";
                return "{$keyVal1}\n{$keyVal2}";
            case 'nested':
                $formattedChildren = array_map(fn ($child) => $iter($child, $depth + 1), getChildren($currentData));
                $formattedLines =  ['{', ...$formattedChildren, "{$currentIndent}}"];
                $string =  implode("\n", $formattedLines);
                return "{$currentIndent}{$currentData['key']}: {$string}";
            default:
                throw new \Exception('Not valid differ of node!');
        }
    };
    $result =  implode("\n", array_map(fn ($node) => $iter($node, 1), $data));
    return "{\n{$result}\n}";
}
