<?php

namespace Code\Differs\Yaml;

use stdClass;
use function Code\Gendiff\toString;

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
function hasChildren($data)
{
    return key_exists('children', $data);
}
function sortAlphabet(&$data)
{
    usort($data, fn($a, $b) => strcmp($a['key'], $b['key']));
   #var_dump($data);
    $data =  array_map(function($val) {
        #var_dump($val);
        if (hasChildren($val)) {
            sortAlphabet($val['children']);
        }
        return $val;
    }, $data);
    #var_dump($data);
    #var_dump($result);
    return $data;
    
}
function sign($diff)
{
    #sortAlphabet($diff);
    #$diff = sortAlphabet($diff);
    #print_r($diff);
    $iter = function($data) use (&$iter){
        
        if (is_object($data['value']) && (!key_exists('status', $data))){ //если класс то значит сложные данные не дети которы могли быть изменены и не изменены
            //поставить знак если требуется и раскраыть в массив
            
            return [$data['key'] => $data['value']]; //group3 например
        }
        if (!key_exists('children', $data) && (key_exists('status', $data))) {
            //детей нет есть сатутс может также ообъектом и простым значением
            $status = $data['status'];
            if ($status !== 'changed') {//can be complex and simple
                $sign = $status === 'added' ? '+' : '-';
                $key = "{$sign} {$data['key']}";
                #$data[$key] = $data['value'];
                $data[$key] = $data['value'];
                #return [$key => $data['value']];
            }else{
                $data["- {$data['key']}"] = $data['value']['oldValue'];
                $data["+ {$data['key']}"] = $data['value']['newValue'];
            }
            unset($data['key'], $data['value'], $data['status']);
        }

        if (key_exists('children', $data) && (!key_exists('status', $data))) {//есть дети но нет статуса
            $children = ($data['children']);
            #var_dump($children);
            $newChildren = array_merge(...array_map(fn($child) => $iter($child), $children));
            #$newChildren = array_merge(array_map(fn($child) => $iter($child), $children)); original среда вечер!!!
            #var_dump($newChildren);
            $data[$data['key']] =$newChildren;
            unset($data['key'], $data['value'], $data['status'], $data['children']);
            
        }
        #var_dump($data);
        return [...$data];
    };

    return [array_merge(...(array_map(fn($data) => $iter($data), $diff)))]; # вечер среды о этого было: (array_map(fn($data) => $iter($data), $diff)));
}
function isChanged($data)
{
    return key_exists('status', $data);
}

function getSign(string $status)
{
    switch($status) {
        case 'added':
            return '+';
        case 'deleted':
            return '-';
        default:
            return '';    
    }
}

function getChildren($data)
{
    #var_dump($data);
    return $data['children'];
}

function objectTAarray($data)
{
    
    if (!is_object($data) && !is_array($data)) {
        return $data;
    }
    //если массив, фильтруем проверяем значения
    if (is_array($data)) {
        #var_dump($data);
            if (key_exists('oldValue', $data) && key_exists('newValue', $data)){
                return array_map(fn($value) =>objectTAarray($value), $data);
            }
            
            return objectTAarray($data);   
    }

    $arrayOfProperties = get_object_vars($data);
    $result = array_map(fn($value) => objectTAarray($value), $arrayOfProperties);
    return $result;
}
function addSign($diff)
{
   
    $iter = function($data) use(&$iter) {
        if (!hasChildren($data)) {
            $status = isChanged($data) ? $data['status'] : '';
            /*
            if (is_object($data['value'])) {
                $data['value'] = get_object_vars($data['value']);
            }
            */
            $data['value'] = objectTAarray($data['value']);
            
            if ($status === 'changed') {
                $data["- {$data['key']}"] = $data['value']['oldValue'];
                $data["+ {$data['key']}"] = $data['value']['newValue'];
            }else {
                $sign = getSign($status);
                $key = $sign !== '' ? "{$sign} {$data['key']}" : "{$data['key']}";
                #$data[$key] = $data['value'];
                return [$key => $data['value']];
            }
            unset($data['key'], $data['value'], $data['status']);
            #return [$data['key'] => $data['value']];;
            return $data;
        } 
       
        $children = getChildren($data);
        $newChildren = array_merge(...array_map(fn($child) => $iter($child), $children));
        $data[$data['key']] =$newChildren;
        unset($data['key'], $data['value'], $data['status'], $data['children']);
        return $data;
    };
    return [array_merge(...(array_map(fn($data) => $iter($data), $diff)))];
}

function stringify($data) {
    {
        $iter = function ($currentValue, $depth) use (&$iter) {
            if (!is_array($currentValue)) {
                return toString($currentValue);
            }
            $spacesCount=4;
            $replacer = ' ';
            $indentSize = $depth * $spacesCount;
            $currentIndent = str_repeat($replacer, $indentSize);
            $signIndent = str_repeat($replacer, ($indentSize-2));
            $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);
            $lines = array_map(function($key, $val) use ($currentIndent, $signIndent, &$iter, &$depth) {
                if ($key[0] === '+' || $key[0] === '-'){
                    return "{$signIndent}{$key}: {$iter($val, $depth+1)}";
                }
                return "{$currentIndent}{$key}: {$iter($val, $depth+1)}";  
            }, array_keys($currentValue), $currentValue);
            
                    $result = ['{', ...$lines, "{$bracketIndent}}"];
                    #$result = [...$lines, "{$bracketIndent}}"];
            return implode("\n", $result);
        };
        
    
        return $iter($data, 1);
    }
}
function diff($a, $b)
{
    $result = compare($a,$b);
    
    sortAlphabet($result);
    #var_dump($result);
    #$result = sign($result);
    $result = addSign($result);
    $result = stringify(array_merge(...$result)) . "\n";
    return $result;
}