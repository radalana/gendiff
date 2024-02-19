<?php

namespace Differ\Formatters\Stylish;

use Exception;

use function Differ\Differ\getValue;
use function Differ\Differ\hasChildren;
use function Differ\Differ\getChildren;
use function Differ\Differ\isIndexedArray;
use function Differ\Differ\isChanged;
use function Differ\Differ\toString;

const SPACES_COUNT = 4;
const REPLACER = ' ';

function objectTAarray(mixed $data): mixed
{
    //if data is a primitive data
    if (!is_object($data) && !is_array($data)) {
        return $data;
    }
    //if data is an array
    if (is_array($data)) {
        if (key_exists('value1', $data) && key_exists('value2', $data)) {
            return array_map(fn($value) =>objectTAarray($value), $data);
        }
            return $data;
    }

    $arrayOfProperties = get_object_vars($data);
    $result = array_map(fn($value) => objectTAarray($value), $arrayOfProperties);
    return $result;
}

/**
 * @param array<int|string, mixed> $diff
 * @return array<mixed>
 */
function addSign(array $diff): array
{
    $iter = function ($data) use (&$iter) {
        if (!hasChildren($data)) {
            $val = getValue($data);
            $diff = isChanged($data) ? $data['differ'] : '';
            $arrayVal = objectTAarray($val);

            if ($diff === 'changed') {
                $val1 = getValue($data, 'old');
                $val2 = getValue($data, 'new');
                return ["- {$data['key']}" => objectTAarray($val1), "+ {$data['key']}" => objectTAarray($val2)];
            } elseif ($diff === 'added') {
                return ["+ {$data['key']}" => $arrayVal];
            } elseif ($diff === 'deleted') {
                return ["- {$data['key']}" => $arrayVal];
            } else {
                return ["{$data['key']}" => $arrayVal];
            }
        }

        $children = getChildren($data);
        #var_dump($children);
        $newChildren = array_merge(...array_map(fn($child) => $iter($child), $children));
        return [$data['key'] => $newChildren];
    };
    return [array_merge(...(array_map(fn($data) => $iter($data), $diff)))];
}


/**
 * @param array<string, mixed> $data
 * @return string
 */
function stringify(array $data): string
{
    {
        $iter = function (mixed $currentValue, int $depth) use (&$iter) {
            if (!is_array($currentValue)) {
                return toString($currentValue);
            }

            $indentSize = $depth * SPACES_COUNT;
            $currentIndent = str_repeat(REPLACER, $indentSize);
            $signIndent = str_repeat(REPLACER, ($indentSize - 2));
            $bracketIndent = str_repeat(REPLACER, $indentSize - SPACES_COUNT);
            if (isIndexedArray($currentValue)) { //для обвчного массива
                $string = implode(', ', $currentValue);
                return "[{$string}]";
            }
            $lines = array_map(function ($key, $val) use ($currentIndent, $signIndent, &$iter, &$depth) {
                if (is_string($key) && ($key[0] === '+' || $key[0] === '-')) {
                    return "{$signIndent}{$key}: {$iter($val, $depth+1)}";
                }
                if (is_int($key)) {
                    return "{$currentIndent}{$iter($val, $depth+1)}";
                }
                return "{$currentIndent}{$key}: {$iter($val, $depth+1)}";
            }, array_keys($currentValue), $currentValue);
                     $result = ['{', ...$lines, "{$bracketIndent}}"];

            return implode("\n", $result);
        };
        return $iter($data, 1);
    }
}

/**
 * @param array<int|string, mixed> $ast
 * @return string
 */
function format(array $ast): string
{
    $arrayWithSigns = addSign($ast);
    return stringify(array_merge(...$arrayWithSigns));
}
