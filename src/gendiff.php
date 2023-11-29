<?php

namespace Code\Differ;

$data1 = '{
    "host": "hexlet.io",
    "timeout": 50,
    "proxy": "123.234.53.22",
    "follow": false,
    "verbose": false
  }';

$data2 = '{
    "timeout": 20,
    "verbose": true,
    "host": "hexlet.io",
    "proxy": "123.234.53.22"
  }';
function toString(mixed $value): string
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }
    return (string) $value;
}

function defineSign(array $data, string $sign): array
{
    foreach ($data as $key => $value) {
        $value = toString($value);
        if (in_array($value, $commonKeys)) {
            if (in_array($value, $diffValues)) { //есть общие ключи, и разные значения
                $result["{$key}: {$value}"] = "-";
            } else { //есть общие ключи и значение одинаковы
                $result["{$key}: {$value}"] = " ";
            }
        } else {
            $result["{$key}: {$value}"] = '-';
        }
    }
}

function gendiff(string $pathTofile1, string $pathTofile2)
{
    $data1 = $pathTofile1;
    $data2 = $pathTofile2;

    $arrayOfData1 = json_decode($data1, true);
    $arrayOfData2 = json_decode($data2, true);

    $commonValues = array_intersect_assoc($arrayOfData1, $arrayOfData2); //["host"]=>"hexlet.io"
    $commonKeys = array_intersect_key($arrayOfData1, $arrayOfData2); //["host"]=>"hexlet.io",["timeout"]=>50
    $diffValues = array_diff_assoc($commonKeys, $commonValues); //["timeout"]=>50  
    $result = [];

    foreach ($arrayOfData1 as $key => $value) {
        $value = toString($value);
        if (in_array($value, $commonKeys)) {
            if (in_array($value, $diffValues)) { //есть общие ключи, и разные значения
                $result["{$key}: {$value}"] = "-";
            } else { //есть общие ключи и значение одинаковы
                $result["{$key}: {$value}"] = " ";
            }
        } else {
            $result["{$key}: {$value}"] = '-';
        }
    }

    foreach ($arrayOfData2 as $key => $value) {
        $value = toString($value);
        if (in_array($value, $commonKeys)) {
            if (in_array($value, $diffValues)) { //есть общие ключи, и разные значения
                $result["{$key}: {$value}"] = '+';
            } else { //есть общие ключи и значение одинаковы
                $result["{$key}: {$value}"] = " ";
            }
        } else {
            $result["{$key}: {$value}"] = '+';
        }

    }
    // var_dump($result);
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

    $parts = [];
    foreach ($result as $key => $value) {
        $parts[] = "{$value} {$key}";
    }

    $string = implode("\n", $parts);
    return $string;
}

print_r(gendiff($data1, $data2));