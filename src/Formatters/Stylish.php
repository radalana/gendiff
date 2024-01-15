<?php

namespace Code\Formatters\Stylish;

use function Code\Gendiff\hasChildren;
/*
function toString($value)
{
     return trim(var_export($value, true), "'");
}
*/

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

uksort(
        $result,
        function ($a, $b) use ($result) {
            if (strcmp(strstr($a, ':', true), strstr($b, ':', true)) === 0) { // тут ключи не могут быть одинаковыми
                // var_dump($a, $b);
                if ($result[$a] === '-' && $result[$b] === '+') {
                    return -1;
                } else if ($result[$a] === '+' && $result[$b] === '-') {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return strcmp($a, $b);
            }

        }
    );
*/
/*
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
    sortAlphabet($diff);
    #$diff = sortAlphabet($diff);
    #print_r($diff);
    $iter = function($data) use (&$iter){
        if (!key_exists('children', $data) && (!key_exists('status', $data))){        
            return [$data['key'] => $data['value']]; //простое неизменноное значение 
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
        }

        if (key_exists('children', $data) && (!key_exists('status', $data))) {
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

function style($data)
{
    $array = (sign($data));
    return stringify(array_merge(...$array)) . "\n";
    #return array_merge(...$array);
    #return json_encode(array_merge(...$array), JSON_PRETTY_PRINT);
}
*/

//jan 12
function toString($value) {
    if (is_bool($value) || is_null($value)) {
        return strtolower(var_export($value, true));
    }
    return $value;
}


/*
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
*/
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
            
            #return objectTAarray($data);
            #var_dump($data);
            return $data;   
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
function isIndexedArray($array)
{
        return array_values($array) === $array;
}
function stringify($data) {
    {
        $iter = function ($currentValue, $depth) use (&$iter) {
            if (!is_array($currentValue)) {
                #var_dump($currentValue);
                return toString($currentValue);
            }
            
            $spacesCount=4;
            $replacer = ' ';
            $indentSize = $depth * $spacesCount;
            $currentIndent = str_repeat($replacer, $indentSize);
            $signIndent = str_repeat($replacer, ($indentSize-2));
            $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);
            if (isIndexedArray($currentValue)) { //для обвчного массива
                $string = implode(', ', $currentValue);
                return "[{$string}]";
            }
            $lines = array_map(function($key, $val) use ($currentIndent, $signIndent, &$iter, &$depth) {
                if (is_string($key) && ($key[0] === '+' || $key[0] === '-')) {
                    return "{$signIndent}{$key}: {$iter($val, $depth+1)}";
                }
                if (is_int($key)) {
                    return "{$currentIndent}{$iter($val, $depth+1)}";
                }
                return "{$currentIndent}{$key}: {$iter($val, $depth+1)}";  
            }, array_keys($currentValue), $currentValue);
                    //если индексированный массив добавить здесь условие квадратных скобок
                     $result = ['{', ...$lines, "{$bracketIndent}}"];

                    #$result = [...$lines, "{$bracketIndent}}"];
            return implode("\n", $result);
        };
        
    
        return $iter($data, 1);
    }
}

function style($ast)
{
    #sortAlphabet($ast);
    $arrayWithSigns = addSign($ast);
    #return json_encode($arrayWithSigns, JSON_PRETTY_PRINT);
    #return $arrayWithSigns;
    return stringify(array_merge(...$arrayWithSigns)) . "\n";
}