<?php

namespace Differ\Formatters\Stylish;

use function Differ\Differ\getValue;
use function Differ\Differ\hasChildren;

const SPACES_COUNT = 4;
const REPLACER = ' ';
function toString(mixed $value): mixed
{

    if (is_bool($value) || is_null($value)) {
        return strtolower(var_export($value, true));
    }
    return $value;
}

/**shows if current data if was added/deleted/changed */
/**
 * @param array<string, mixed> $data
 * @return bool
 */
function isChanged(array $data): bool
{
    return key_exists('status', $data);
}

function getSign(string $status): string
{
    switch ($status) {
        case 'added':
            return '+';
        case 'deleted':
            return '-';
        default:
            return '';
    }
}

/**
 * @param array<string, mixed> $data
 * @return array<mixed>
 */
function getChildren(array $data): array
{
    return $data['children'];
}

function objectTAarray(mixed $data): mixed
{
    //if data is a primitive data
    if (!is_object($data) && !is_array($data)) {
        return $data;
    }
    //if data is an array
    if (is_array($data)) {
        if (key_exists('oldValue', $data) && key_exists('newValue', $data)) {
            return array_map(fn($value) =>objectTAarray($value), $data);
        }
            return $data;
    }

    $arrayOfProperties = get_object_vars($data);
    $result = array_map(fn($value) => objectTAarray($value), $arrayOfProperties);
    return $result;
}

/**
 * @param array<string, mixed> $diff
 * @return array<mixed>
 */
function addSign(array $diff): array
{
    $iter = function ($data) use (&$iter) {
        if (!hasChildren($data)) {
            $val = getValue($data);
            $status = isChanged($data) ? $data['status'] : '';
            $arrayVal = objectTAarray($val);

            if ($status === 'changed') {
                $oldVal = getValue($data, 'old');
                $newVal = getValue($data, 'new');
                return ["- {$data['key']}" => objectTAarray($oldVal), "+ {$data['key']}" => objectTAarray($newVal)];
            }
            $sign = getSign($status);
            $key = $sign !== '' ? "{$sign} {$data['key']}" : "{$data['key']}";
            return [$key => $arrayVal];
        }
        $children = getChildren($data);
        $newChildren = array_merge(...array_map(fn($child) => $iter($child), $children));
        return [$data['key'] => $newChildren];
    };
    return [array_merge(...(array_map(fn($data) => $iter($data), $diff)))];
}
function isIndexedArray(mixed $value): bool
{
    if (is_array($value)) {
        return array_values($value) === $value;
    }
        return false;
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
function style(array $ast): string
{
    $arrayWithSigns = addSign($ast);
    return stringify(array_merge(...$arrayWithSigns));
}
