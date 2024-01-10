<?php

namespace Code\Stylish;

use function Funct\Collection\flatten;
use function Funct\Collection\flattenAll;
function toString($value)
{
     return trim(var_export($value, true), "'");
}
/*
function style(array $data)
{
    var_dump($data);
    $iter = function($currentValue, $depth) use (&$iter) {
        if (!is_array($currentValue) && !key_exists(['value'])){
            return toString($currentValue);
        }
        if (key_exists('value', $currentValue) && !is_array($currentValue['value'])) {
            return toString($currentValue['value']);
        }
        
        if (!key_exists('children', $currentValue) && !is_array($currentValue['value'])){
            
            if (key_exists('status', $currentValue)) {
                $key = $currentValue['key'];
                
                switch ($currentValue['status']) {
                    case 'added':
                        return "+ {$key}: {$currentValue['value']}";
                    case 'deleted':
                        return "- {$key}: {$currentValue['value']}";
                    case 'changed':
                        return "- {$key}: {$currentValue['value']['oldValue']}, + {$key}: {$currentValue['value']['newValue']}";
                    default:
                        return "{$key}: {$currentValue['value']}";
                }
            }
            
            return $currentValue['value'];
        }

        
        $indentSize = $depth * 4;
        $currentIndent = str_repeat(' ', $indentSize);
        $bracketIndent = str_repeat(' ', $indentSize - 4);
        if (key_exists('children', $currentValue)) {
            $children = $currentValue['children'];
            $lines = array_map(fn($child)  => "{$currentIndent}{$child['key']}: {$iter($child, $depth + 1)}", $children);
        }
        /*
        if (key_exists('status', $currentValue) && (is_array($currentValue['value']))) {
            var_dump($currentValue);
            #$lines = array_map(fn($val) => "{$currentIndent}{$val['key']}: {$iter($val['value'], $depth + 1)}",
            #$currentValue);
            return  
        }*/
/*
        if (key_exists('status', $currentValue) && (is_array($currentValue['value']))) {
            #var_dump($currentValue);
            $sign = $currentValue['status'] === 'added' ? '+' : '-';
            $lines = "{$currentIndent}{$sign} {$currentValue['key']}: {$iter($currentValue['value'], $depth + 1)}";
        }
        
        if (!key_exists('key', $currentValue)) {
            var_dump($currentValue);
            $lines = array_map(fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $depth + 1)}",
            array_keys($currentValue), $currentValue);
        }
        

        $result = ['{', $lines, "{$bracketIndent}}"];
        return implode("\n", $result);

    };
    #$result = $iter($data, 1);
    $result = array_map(fn($value) => ($iter($value, 1)), $data);
    return $result;
}

*/

/*
function sign($diff)
{
    $iter = function($data) use (&$iter){
        if (!key_exists('children', $data) && (key_exists('status', $data))) {
            //changed not here
            $status = $data['status'];
            if ($status !== 'changed') {//can be complex and simple
                $sign = $status === 'added' ? '+' : '-';
                $data['key'] = "{$sign} {$data['key']}"; //can be complex and simple
                unset($data['status']);
            }
        }

        if (key_exists('children', $data) && (!key_exists('status', $data))) {
            $children = $data['children'];
            $newChildren = array_map(fn($child) => $iter($child), $children);
            $data['children'] = $newChildren;
            return $data;
        }
        return $data;
    };

    return array_map(fn($data) => $iter($data), $diff);
}
*/
function isAssociative($data) {
    if (is_array($data) && array_keys($data) !== range(0, count($data) -1)){
        return true;
    }
    return false;
}


function sign($diff)
{
    $iter = function($data) use (&$iter){
        if (!key_exists('children', $data) && (!key_exists('status', $data))){
            /*
            $data[$data['key']] = $data['value'];
            unset($data['value']);
            */
            #return [$data['key'] => $data['value']];
            $data[$data['key']] = $data['value'];
            return $data;
        }
        if (!key_exists('children', $data) && (key_exists('status', $data))) {
            //changed not here
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
            #flatten($data);
        }

        if (key_exists('children', $data) && (!key_exists('status', $data))) {
            $children = ($data['children']);
            #var_dump($children);
            $newChildren = array_merge(...array_map(fn($child) => $iter($child), $children));
            #$newChildren = array_merge(array_map(fn($child) => $iter($child), $children)); original среда вечер!!!
            var_dump($newChildren);
            $data[$data['key']] = $newChildren;
            unset($data['key'], $data['value'], $data['status'], $data['children']);
            
        }
        #var_dump($data);
        return [...$data];
    };

    return [array_merge(...(array_map(fn($data) => $iter($data), $diff)))]; # вечер среды о этого было: (array_map(fn($data) => $iter($data), $diff)));
}
function isOrdered($array)
{
    if (is_array($array) && (array_keys($array) === range(0, count($array) -1))){
        return true;
    }
    return false;
}

/*
function stringify($data) {
    {
        $iter = function ($currentValue, $depth) use (&$iter) {
            if (!is_array($currentValue)) {
                return toString($currentValue);
            }
            $replacer = ' ';
            $indentSize = $depth * 4;
            $currentIndent = str_repeat($replacer, $indentSize);
            $bracketIndent = str_repeat($replacer, $indentSize - 4);
            $lines = array_map(function($key, $val) use ($currentIndent, &$iter, &$depth) {
                return "{$currentIndent}{$key}: {$iter($val, $depth+1)}";  
            }, array_keys($currentValue), $currentValue);
            
                    $result = ['{', ...$lines, "{$bracketIndent}}"];
                    #$result = [...$lines, "{$bracketIndent}}"];
            return implode("\n", $result);
        };
        
    
        return $iter($data, 1);
    }
}
*/
function style($data)
{
    $array = array_values(sign($data));
    return json_encode($array, JSON_PRETTY_PRINT);
}