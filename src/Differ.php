<?php

namespace Differ\Differ;

use Exception;

use function Functional\sort as funcSort;
use function Differ\Parsers\parse;
use function Differ\Formatters\format;

/**
 * @param array<string, mixed> $data
 * @return array<mixed>
 */
function getChildren(array $data): array
{
    return $data['children'];
}

function isIndexedArray(mixed $value): bool
{
    if (is_array($value)) {
        return array_values($value) === $value;
    }
        return false;
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

function toString(mixed $value): mixed
{

    if (is_bool($value) || is_null($value)) {
        return strtolower(var_export($value, true));
    }
    return $value;
}

function readFromFile(string $pathTofile): string
{
    $data = file_get_contents($pathTofile);
    if ($data === false) {
        throw new Exception('Failure to open/read file!');
    }
    return $data;
}
/**
 * @param array<int|string, mixed> $ast
 * @return array<string, mixed>
 */
function sortAst(array $ast): array
{
    $iter = function (array $node) use (&$iter): array {
        if (!hasChildren($node)) {
            return $node;
        }
        $children = getChildren($node);
        $sortedChildren = funcSort($children, fn($a, $b) => strcmp($a['key'], $b['key']), true);
        return ['key' => $node['key'], 'children' => array_map(fn($child) => $iter($child), $sortedChildren)];
    };
    return array_map(fn($node) => $iter($node), $ast);
}

/**
 * @param  array<int|string, mixed>$data
 * @return array<string, mixed>
 */
function sortData(array $data): array
{
    $dataWithSortedNodes = sortAst($data);
    #var_dump($dataWithSortedNodes);
    $sortedData = funcSort($dataWithSortedNodes, fn($a, $b) => strcmp($a['key'], $b['key']), true);
    return $sortedData;
}
/**
* @param mixed $a
* @param mixed $b
* @return array<int|string, mixed>
*/
function compare(mixed $a, mixed $b): mixed
{
    if (!is_object($a) || !is_object($b)) {
        if ($a === $b) {
            return ['value'  => $a];
        } else {
            return ['value' => ['oldValue'  => $a, 'newValue'  => $b], 'status' => 'changed'];
        }
    }
    $properiesOfa = get_object_vars($a);
    $properiesOfb = get_object_vars($b);
    $commonProperties = (array_intersect_key($properiesOfa, $properiesOfb));//общие свойства объектов
    $commonData = array_map(
        function (string $commonProperty) use ($properiesOfa, $properiesOfb): array {
            $iter = compare($properiesOfa[$commonProperty], $properiesOfb[$commonProperty]);
            if (is_object($properiesOfa[$commonProperty]) && is_object($properiesOfb[$commonProperty])) {
                return ['key' => $commonProperty, 'children' => $iter];
            } else {
                return ['key' => $commonProperty, ...$iter];
            }
        },
        array_keys($commonProperties)
    );

    $deletedKeys = array_keys(array_diff_key($properiesOfa, $commonProperties));
    $deletedData = array_map(
        fn($deletedKey) => ['key' => $deletedKey, 'value' => ($properiesOfa[$deletedKey]), 'status' => 'deleted'],
        $deletedKeys
    );

    $addedKeys = array_keys(array_diff_key($properiesOfb, $commonProperties));

    $addedData = array_map(
        fn($addedKey) => ['key' => $addedKey, 'value' => ($properiesOfb[$addedKey]), 'status' => 'added'],
        $addedKeys
    );
    return sortData(array_merge($addedData, $commonData, $deletedData));//просто сортировать здесь?....
}
/**
 * @param array<string, mixed> $data
 * @return bool
 */
function hasChildren(array $data): bool
{
    return key_exists('children', $data);
}
/**
 * @param array<string, mixed> $data
 * @param string $oldNew
 * @return mixed
 */
function getValue(array $data, string $oldNew = ''): mixed
{
    if ($oldNew === '') {
        return $data['value'];
    }
    return $oldNew === 'old' ? $data['value']['oldValue'] : $data['value']['newValue'];
}

function getFilesType(string $path1, string $path2): string
{
    $type1 = pathinfo($path1, PATHINFO_EXTENSION);
    $type2 = pathinfo($path2, PATHINFO_EXTENSION);
    if ($type1 !== $type2) {
        throw new \Exception('Files must be of the same type!');
    }
    return $type1;
}
function gendiff(string $path1, string $path2, string $formatName = 'stylish'): string
{
    $stringData1 = readFromFile($path1);
    $stringData2 = readFromFile($path2);

    $type = getFilesType($path1, $path2);
    $data1 = parse($stringData1, $type);
    $data2 = parse($stringData2, $type);

    $ast = compare($data1, $data2);
    return format($formatName, $ast);
}
