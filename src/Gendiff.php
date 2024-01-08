<?php
namespace Code\Gendiff;


use function Code\Parsers\getData;
//use function Functional\some;
/*
$string = file_get_contents('../tests/fixtures/jsonFiles/file1.json');
$data1 = json_decode($string, true);
$data2 = json_decode(file_get_contents('../tests/fixtures/jsonFiles/file2.json'), true);

*/
function toString($value) {
    if (is_bool($value) || (is_null($value))) {
        return var_export($value, true);
    }
    return $value;
}

function isAssociative($data) {
    if (is_array($data) && array_keys($data) !== range(0, count($data) -1)){
        return true;
    }
    return false;
}

/* пока оказалась не нужна и не нужно вводить some
function hasChildren($node) {//['wow' => 'so much'] associative
    if (is_array($node)) {
        return some($node, fn($element)  => isAssociative($element));
    }
    var_dump('Has not children:', $node);
    return false;
}
*/

function compare($a, $b)
{

    if (!is_array($a) || !is_array($b)){#для вложенных по другому
        if ($a === $b) {
            return ['value'  => toString($a)];
        }else {
            return ['status' => ['oldValue'  => toString($a), 'newValue'  => toString($b)]];
        }
    }
    
    $commonKeys = (array_intersect_key($a, $b));
    $commonData = array_map(function($commonKey) use ($a, $b){
        $iter = compare($a[$commonKey], $b[$commonKey]);
        if (isAssociative($a[$commonKey]) && (isAssociative($b[$commonKey]))) {//original:  (hasChildren($a[$commonKey]) && (hasChildren($b[$commonKey])))
            var_dump('has children', $a[$commonKey]);
            var_dump('and has children', $b[$commonKey]);
            return ['key' => $commonKey, 'children' => $iter]; //expected: 'key' => 'doge', 'children' => ['wow': .....]
        }
        return ['key' => $commonKey, ...$iter];
        
    }, array_keys($commonKeys));

    $deletedKeys = array_keys(array_diff_key($a, $commonKeys));
    $deletedData = array_map(fn($deletedKey) => ['key' => $deletedKey, 'value' => toString($a[$deletedKey]), 'status' => 'deleted'], $deletedKeys);

    $addedKeys = array_keys(array_diff_key($b, $commonKeys));
    $addedData = array_map(fn($addedKey) => ['key' => $addedKey, 'value' => toString($b[$addedKey]), 'status' => 'added'], $addedKeys);

    return [...$addedData, ...$commonData, ...$deletedData];
}

function gendiff($path1, $path2)
{
    $data1 = json_decode(file_get_contents($path1), true);
    $data2 = json_decode(file_get_contents($path2), true);


    $result = compare($data1, $data2);
    return $result;
}


