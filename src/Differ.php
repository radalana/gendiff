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


function sortAlphabetic(array $data): array
{
    $iter = function ($node) use (&$iter) {
        if (!hasChildren($node)) {
            return $node;
        }
        $children = getChildren($node);
        $sortedChildren = funcSort($children, fn($a, $b) => strcmp($a['key'], $b['key']), true);


        $node['children'] = array_map(fn($child) => $iter($child), $sortedChildren);
        return $node;
    };
    $sortedNodes = array_map(fn($value) => $iter($value), $data);
    #var_dump($sortedNodes);
    return funcSort($sortedNodes, fn($a, $b) => strcmp($a['key'], $b['key']), true);
}


function gendiff(string $path1, string $path2, string $formatName = 'stylish')#: string
{
    $data1 = getData($path1);
    $data2 = getData($path2);

    $ast = compare($data1, $data2);
    #print_r($ast);
    #print_r("---------------------------------------------------------------------------------------------------");
    $sortedAst = sortAlphabetic($ast);
    return format($formatName, $sortedAst);
    #return $sortedAst;
}
