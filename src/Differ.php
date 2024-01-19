<?php

namespace Differ\Differ;

use stdClass;

use function Functional\sort as funcSort;
use function Differ\Parsers\getData;
use function Differ\Formatters\format;
use function Differ\Formatters\Stylish\getChildren;

/**
 * compare 2 datas and  generate ast
 */
function compare(mixed $a, mixed $b): array
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
    //Should not use of mutating operators
    /*
    $commonData = array_reduce(
        array_keys($commonProperties),
        function ($acc, $commonProperty) use ($properiesOfa, $properiesOfb) {
            $iter = compare($properiesOfa[$commonProperty], $properiesOfb[$commonProperty]);

            if (is_object($properiesOfa[$commonProperty]) && (is_object($properiesOfb[$commonProperty]))) {//если
                $acc[] = ['key' => $commonProperty, 'children' => $iter];
            } else {
                $acc[] = ['key' => $commonProperty, ...$iter];
            }
            return $acc;
        },
        []
    );
    */
    $commonData = array_map(
        function ($commonProperty) use ($properiesOfa, $properiesOfb) {
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
    return array_merge($addedData, $commonData, $deletedData);
}

function hasChildren(array $data): bool
{
    return key_exists('children', $data);
}

function getValue(array $data, string $oldNew = ''): mixed
{
    if ($oldNew === '') {
        return $data['value'];
    }
    return $oldNew === 'old' ? $data['value']['oldValue'] : $data['value']['newValue'];
}
/*
function sortAlphabetic(array $data): array
{
    $iter = function ($node) use (&$iter) {
        if (!hasChildren($node)) {
            return $node;
        }
        $children = getChildren($node);
        $sortedChildren = funcSort($children, fn($a, $b) => strcmp($a['key'], $b['key']), true);
        $newChildren = array_map(fn($child) => $iter($child), $sortedChildren);
        $node['children'] = $newChildren;
        return $node;
    };
    $sortedNodes = array_map(fn($value) => $iter($value), $data);
    #var_dump($sortedNodes);
    return funcSort($sortedNodes, fn($a, $b) => strcmp($a['key'], $b['key']), true);
}
*/

/*
function sortAst(array $node): array
{
    if (!hasChildren($node)) {
        return $node;
    }
    $children = getChildren($node);
    $sortedChildren = funcSort($children, fn($a, $b) => strcmp($a['key'], $b['key']), true);
    return ['key' => $node['key'], 'children' => array_map(fn($child) => sortAst($child), $sortedChildren)];
}

function sortData(array $data): array
{
    return array_map(fn($val) => sortAst($val), $data);
} 
*/

function sortAst(array $ast): array
{
    $iter = function($node) use (&$iter) {
        if (!hasChildren($node)) {
            return $node;
        }
        $children = getChildren($node);
        $sortedChildren = funcSort($children, fn($a, $b) => strcmp($a['key'], $b['key']), true);
        return ['key' => $node['key'], 'children' => array_map(fn($child) => $iter($child), $sortedChildren)];
    };
    return array_map(fn($node) => $iter($node), $ast);
}
function sortData(array $data): array
{
    $dataWithSortedNodes = sortAst($data);
    #var_dump($dataWithSortedNodes);
    $sortedData = funcSort($dataWithSortedNodes, fn($a, $b) => strcmp($a['key'], $b['key']), true);
    return $sortedData;
}
function gendiff(string $path1, string $path2, string $formatName = 'stylish')
{
    $data1 = getData($path1);
    $data2 = getData($path2);

    $ast = compare($data1, $data2);
    #return sortData($ast);
    #print_r($ast[1]);
    #print_r("------------------------------");
    #return sortAst($ast[1]);
    $sortedAst = sortData($ast);
    return format($formatName, $sortedAst);
    #return format($formatName, $sortedAst);
}
