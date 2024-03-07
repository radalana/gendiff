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
            return array_map(fn($value) =>objectToArray($value), $data);
        }
            return $data;
    }
    $arrayOfProperties = get_object_vars($data);
    $result = array_map(fn($value) => objectToArray($value), $arrayOfProperties);
    return $result;
}

/**
 * @param array<int|string, mixed> $diff
 * @return array<mixed>
 */
function addSign1(array $diff): array
{
    $iter = function ($data) use (&$iter) {
        $differ = $data['differ'];
        switch ($differ) {
            case 'nested':
                $children = getChildren($data);
                $newChildren = array_merge(...array_map(fn($child) => $iter($child), $children));
                return [$data['key'] => $newChildren];
            case 'added':
                //var_dump('это условие не должно сработать, что за пиздец');
                return ["+ {$data['key']}" => objectToArray($data['value'])];
            case 'deleted':
                return ["- {$data['key']}" => objectToArray($data['value'])];
            case 'changed':
                $val1 = $data['value1'];
                $val2 = $data['value2'];
                return ["- {$data['key']}" => objectToArray($val1), "+ {$data['key']}" => objectToArray($val2)];
            case 'unchanged':
                return ["{$data['key']}" => objectToArray($data['value'])];
            default:
                throw new \Exception('Not valid differ of node!');
        }
    };
    return [array_merge(...(array_map(fn($data) => $iter($data), $diff)))];
}


/**
 * @param array<int|string, mixed> $ast
 * @return string
 */
function format1(array $ast): string
{
    $arrayWithSigns = addSign($ast);
    return stringify(array_merge(...$arrayWithSigns));
}
function stringify2(array $data): string
{
    
    {
        $iter = function (mixed $currentData, int $depth) use (&$iter) {
            if (!is_array($currentData)) {
                return toString($currentData);
            }

            $indentSize = $depth * SPACES_COUNT;
            $currentIndent = str_repeat(REPLACER, $indentSize);
            $signIndent = str_repeat(REPLACER, ($indentSize - 2));
            $bracketIndent = str_repeat(REPLACER, $indentSize - SPACES_COUNT);
            if (isIndexedArray($currentData)) { //для обвчного массива
                $string = implode(', ', $currentData);
                return "[{$string}]";
            }
            $lines = array_map(function ($key, $val) use ($currentIndent, $signIndent, &$iter, &$depth) {
                ////var_dump($key);
                if (is_string($key) && ($key[0] === '+' || $key[0] === '-')) {
                    return "{$signIndent}{$key}: {$iter($val, $depth+1)}";
                }
                if (is_int($key)) {
                    return "{$currentIndent}{$iter($val, $depth+1)}";
                }
                return "{$currentIndent}{$key}: {$iter($val, $depth+1)}";
            }, array_keys($currentData), $currentData);
                     $result = ['{', ...$lines, "{$bracketIndent}}"];

            return implode("\n", $result);
        };
        return $iter($data, 1);
    }
}
function stringify1($data, $depth) {
    if (!is_array($data)) {
        return toString($data);
    }
    $indentSize = $depth * SPACES_COUNT;
    $currentIndent = str_repeat(REPLACER, $indentSize);
    $bracketIndent = str_repeat(REPLACER, $indentSize - SPACES_COUNT);
    $lines = array_map(function($key, $value) use ($currentIndent, &$depth) {
        $value = stringify1($value, $depth + 1);
        return "{$currentIndent}{$key}: {$value}";

    }, array_keys($data), $data);
    $result = ['{', ...$lines, "{$bracketIndent}}"];
    return implode("\n", $result);

}
function stringify(array $data)
{
    //var_dump($data);
    
        $iter = function (mixed $currentData, int $depth) use (&$iter) {
            if (!is_array($currentData)) {
                return toString($currentData);
            }
            
            $indentSize = $depth * SPACES_COUNT;
            $currentIndent = str_repeat(REPLACER, $indentSize);
            $signIndent = str_repeat(REPLACER, ($indentSize - 2));
            
            //var_dump('изначально', $currentData);
            $differ = $currentData['differ'];
            switch($differ) {
                case 'added':
                     $valAsArray = objectToArray($currentData['value']);
                     //var_dump($valAsArray);
                     $stringified = stringify1($valAsArray, $depth+1);
                     //var_dump($stringified);
                     $added = "{$signIndent}+ {$currentData['key']}: {$stringified}";
                     //print_r("###############################");
                     //var_dump($added);
                     return $added;
                     
                
                case 'deleted':
                    $valAsArray = objectToArray($currentData['value']);
                    $stringified = stringify1($valAsArray, $depth+1);
                    $deleted = "{$signIndent}- {$currentData['key']}: {$stringified}";
                    return $deleted;
                
                case 'unchanged':
                    $valAsArray = objectToArray($currentData['value']);
                    $stringified = stringify1($valAsArray, $depth);
  
                    $unchanged = "{$currentIndent}{$currentData['key']}: {$stringified}";
                    return $unchanged;
                
                case 'changed':
                    $val1 = stringify1(objectToArray($currentData['value1']), $depth+1);
                    $val2 = stringify1(objectToArray($currentData['value2']), $depth+1);
                    $changed = "{$signIndent}- {$currentData['key']}: {$val1}\n{$signIndent}+ {$currentData['key']}: {$val2}";
                    return $changed;
                case 'nested':
                    ////var_dump($currentData);
                    $children = getChildren($currentData);
                    ////var_dump($children);
                    $nested = array_map(fn($child) => $iter($child, $depth+1), $children);
                    //var_dump($nested);
                    $linesNested =  ['{', ...$nested, "{$currentIndent}}"];
                    //var_dump($linesNested);
                    $stringNested =  implode("\n", $linesNested);
                    //var_dump($stringNested);
                    $result = "{$currentIndent}{$currentData['key']}: {$stringNested}";
                    return $result;
                default:
                    throw new \Exception('Not valid differ of node!');
                
            }
        };
        
        $result =  implode("\n", array_map(function($node) use($iter){
            //var_dump($node);
            return $iter($node, 1);
        }, $data));
        return "{\n{$result}\n}";
        
    
}

function format($ast)
{
    $r = stringify($ast);
    ////var_dump($r);
    //print_r("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n");
    //print_r($r);
    #print_r($r);
    return $r;
}