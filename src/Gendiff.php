<?php
namespace Code\Gendiff;

use function Code\Parsers\getData;
/*
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
    "proxy": "123.234.53.20"
  }';
*/
/*
function toString(mixed $value): string
{
    if (is_bool($value)) {
        return $value === true ? 'true' : 'false';
    }
    return (string) $value;
}

function defineSign(array $data, string $sign, array $commonKeys, array $diffValues): array
{
    foreach ($data as $key => $value) {
        $value = toString($value);
        if (in_array($value, $commonKeys)) {
            if (in_array($value, $diffValues)) { //есть общие ключи, и разные значения
                $result["{$key}: {$value}"] = $sign;
            } else { //есть общие ключи и значение одинаковы
                $result["{$key}: {$value}"] = " ";
            }
        } else {
            $result["{$key}: {$value}"] = $sign;
        }
    }
    return $result;
}

//преобразает в требуемый формат
function convertDataToString(array $data)
{
    $parts = [];
    foreach ($data as $key => $value) {
        $parts[] = "{$value} {$key}";
    }

    $string = implode("\n", $parts);
    $string = $string . "\n";
    return "{\n{$string}}\n";
}

function gendiff(string $pathTofile1, string $pathTofile2)
{
    $arrayOfData1 = getData($pathTofile1);
    #var_dump($arrayOfData1);
    $arrayOfData2 = getData($pathTofile2);
    $commonValues = array_intersect_assoc($arrayOfData1, $arrayOfData2);//["host"]=>"hexlet.io"
    $commonKeys = array_intersect_key($arrayOfData1, $arrayOfData2);//["host"]=>"hexlet.io",["timeout"]=>50
    $diffValues = array_diff_assoc($commonKeys, $commonValues);//["timeout"]=>50
    $result = defineSign($arrayOfData1, '-', $commonKeys, $diffValues)
    + defineSign($arrayOfData2, '+', $commonKeys, $diffValues);
    uksort(
        $result,
        function ($a, $b) use ($result) {
            if (strcmp(strstr($a, ':', true), strstr($b, ':', true)) === 0) {//если значения данных совпадают
                if ($result[$a] === '-' && $result[$b] === '+') {
                    return -1;
                } elseif ($result[$a] === '+' && $result[$b] === '-') {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return strcmp($a, $b);
            }
        }
    );
    return convertDataToString($result);
}
*/
