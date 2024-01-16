<?php

namespace Code\Gendiff;

use stdClass;

use function Code\Parsers\getData;
use function Code\Formatters\format;

/**compare 2 datas and  generate ast*/
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

    $commonData = array_reduce(array_keys($commonProperties), function ($acc, $commonProperty) use ($properiesOfa, $properiesOfb) {
        $iter = compare($properiesOfa[$commonProperty], $properiesOfb[$commonProperty]);

        if (is_object($properiesOfa[$commonProperty]) && (is_object($properiesOfb[$commonProperty]))) {//если
            $acc[] = ['key' => $commonProperty, 'children' => $iter];
        } else {
            $acc[] = ['key' => $commonProperty, ...$iter];
        }
        return $acc;
    }, []);

    $deletedKeys = array_keys(array_diff_key($properiesOfa, $commonProperties));
    $deletedData = array_map(fn($deletedKey) => ['key' => $deletedKey, 'value' => ($properiesOfa[$deletedKey]), 'status' => 'deleted'], $deletedKeys);

    $addedKeys = array_keys(array_diff_key($properiesOfb, $commonProperties));

    $addedData = array_map(fn($addedKey) => ['key' => $addedKey, 'value' => ($properiesOfb[$addedKey]), 'status' => 'added'], $addedKeys);
    return array_merge($addedData, $commonData, $deletedData);
}

function hasChildren(array $data): bool
{
    return key_exists('children', $data);
}

function sortAlphabet(&$data): array
{
    usort($data, fn($a, $b) => strcmp($a['key'], $b['key']));
    $data =  array_map(function ($val) {
        if (hasChildren($val)) {
            sortAlphabet($val['children']);
        }
        return $val;
    }, $data);
    return $data;
}
function gendiff(string $path1, string $path2, string $formatName = 'stylish'): string
{
    $data1 = getData($path1);
    $data2 = getData($path2);

    $ast = compare($data1, $data2);
    sortAlphabet($ast);
    return format($formatName, $ast);
}
