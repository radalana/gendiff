<?php
namespace Code\Gendiff;

use function Functional\select_keys;
use function Code\Parsers\getData;
use function Code\Stylish\style;
use function Code\Stylish\sign;

use function Code\Differs\Yaml\compare as compareYml;
use function Code\Differs\Yaml\diff;
//use function Functional\some;
/*
$string = file_get_contents('../tests/fixtures/jsonFiles/file1.json');
$data1 = json_decode($string, true);
$data2 = json_decode(file_get_contents('../tests/fixtures/jsonFiles/file2.json'), true);

*/

function toString($value) {
    if (is_bool($value) || is_null($value)) {
        return strtolower(var_export($value, true));
    }
    return $value;
}
/*
function isAssociative($data) {
    if (is_array($data) && array_keys($data) !== range(0, count($data) -1)){
        return true;
    }
    return false;
}
*/
/* пока оказалась не нужна и не нужно вводить some
function hasChildren($node) {//['wow' => 'so much'] associative
    if (is_array($node)) {
        return some($node, fn($element)  => isAssociative($element));
    }
    var_dump('Has not children:', $node);
    return false;
}
*/
/*
function compare($a, $b)
{
    if (!is_array($a) || !is_array($b)){#для вложенных по другому
        if ($a === $b) {
            return ['value'  => toString($a)];
            #return toString($a);
        }else {
            return ['value' => ['oldValue'  => toString($a), 'newValue'  => toString($b)], 'status' => 'changed'];
        }
    }
    
    $commonKeys = (array_intersect_key($a, $b));
    /*
    $commonData = array_map(function($commonKey) use ($a, $b){
        $iter =  compare($a[$commonKey], $b[$commonKey]);
        #var_dump($commonKey);
        if (isAssociative($a[$commonKey]) && (isAssociative($b[$commonKey]))) {//original:  (hasChildren($a[$commonKey]) && (hasChildren($b[$commonKey])))
            return ['key' => $commonKey, 'children' => $iter]; //expected: 'key' => 'doge', 'children' => ['wow': .....]
            #return [$commonKey => $iter];

        }
        return ['key' => $commonKey, ...$iter];
        #return array_merge(['key' => $commonKey], $iter);

        
    }, array_keys($commonKeys));
    
    #var_dump(array_keys($commonKeys));
    */

    /*
    $commonData = array_reduce(array_keys($commonKeys), function($acc, $commonKey) use ($a, $b) {
        $iter = compare($a[$commonKey], $b[$commonKey]);
        if (isAssociative($a[$commonKey]) && (isAssociative($b[$commonKey]))) {//original:  (hasChildren($a[$commonKey]) && (hasChildren($b[$commonKey])))
            $acc[]= ['key' => $commonKey, 'children' => $iter];

        } else {
            $acc[] = ['key' => $commonKey, ...$iter];
        }
        return $acc;

    }, []);
    
    $deletedKeys = array_keys(array_diff_key($a, $commonKeys));
    $deletedData = array_map(fn($deletedKey) => ['key' => $deletedKey, 'value' => toString($a[$deletedKey]), 'status' => 'deleted'], $deletedKeys);

    $addedKeys = array_keys(array_diff_key($b, $commonKeys));
    $addedData = array_map(fn($addedKey) => ['key' => $addedKey, 'value' => toString($b[$addedKey]), 'status' => 'added'], $addedKeys);
    #return ([...$addedData, ...$commonData, ...$deletedData]);
    return array_merge($addedData, $commonData, $deletedData);
    #return $result;
}
*/
function compare($a, $b) //whicht type?
{
    if (!is_object($a) || !is_object($b)) {
        if ($a === $b) {
            return ['value'  => toString($a)];
        }else {
            return ['value' => ['oldValue'  => toString($a), 'newValue'  => toString($b)], 'status' => 'changed'];
        }
    }
    $properiesOfa = get_object_vars($a);
    $properiesOfb = get_object_vars($b);
    $commonProperties = (array_intersect_key($properiesOfa, $properiesOfb));//общие свойства объектов

    $commonData = array_reduce(array_keys($commonProperties), function($acc, $commonProperty) use ($properiesOfa, $properiesOfb) {
        $iter = compare($properiesOfa[$commonProperty], $properiesOfb[$commonProperty]);

        if (is_object($properiesOfa[$commonProperty]) && (is_object($properiesOfb[$commonProperty]))) {//если 
            $acc[]= ['key' => $commonProperty, 'children' => $iter];

        } else {
            $acc[] = ['key' => $commonProperty, ...$iter];
        }
        return $acc;
        }, []);

    $deletedKeys = array_keys(array_diff_key($properiesOfa, $commonProperties));
    $deletedData = array_map(fn($deletedKey) => ['key' => $deletedKey, 'value' => toString($properiesOfa[$deletedKey]), 'status' => 'deleted'], $deletedKeys);

    $addedKeys = array_keys(array_diff_key($properiesOfb, $commonProperties));
    $addedData = array_map(fn($addedKey) => ['key' => $addedKey, 'value' => toString($properiesOfb[$addedKey]), 'status' => 'added'], $addedKeys);
    return array_merge($addedData, $commonData, $deletedData);
}
function gendiff($path1, $path2)
{
    #$data1 = json_decode(file_get_contents($path1), true);
    #$data2 = json_decode(file_get_contents($path2), true);
    $data1 = getData($path1);
    $data2 = getData($path2);
    #var_dump($data1);
    #$internalRepresentation = compare($data1, $data2);
    #$internalYml = diff($data1, $data2);
    $ast = compare($data1, $data2);
    return style($ast);
    #return $internalYml;
    #return style($internalRepresentation);
    #return sign($internalRepresentation);
    #return $internalRepresentation;
    #return style($internalYml);
}

#тест со списком как отсортирует, только по ключам


