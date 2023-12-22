<?php
namespace Code\Gendiff;


use function Code\Parsers\getData;
use function Functional\some;
$string = file_get_contents('../tests/fixtures/jsonFiles/file1.json');
$data1 = json_decode($string, true);
$data2 = json_decode(file_get_contents('../tests/fixtures/jsonFiles/file2.json'), true);
print_r(some($data1, fn($pairs) => is_bool($pairs)));
/*
function iter($value)
{
    if (!is_array($value)) {
        return trim(var_export($value, true), "'");
    }

    return 1;
}
*/
/*
function compare2(array $a, array $b)
{
    #если узлы разные то все и ставится знаки
    //разница ключей без рассмотра значений diff:      
        //ключ из $a есть в diff тогда, исчез setting2 и ставим "минус",     (если нет то в массиве changed или в same) 
            //функция? если ключ есть в diff, то удаляется и остаются только ключи, которые появились (иипользовать array_reduce?)
        //ключ из появился setting5 
            //если ключ из $b есть в diff, то значение повяилось и ставим "плюс"
    //changed: изменился setting3 true, setting3 null  array_interest_ukey
        $changed = array_intersect_key($a, $b);//вернутся ключи и значения из первого массива, то есть знак минус а так же совпадающие
        #var_dump($changed);

    #если узлы одинаковые то погружаемся на следующий уровень
    //array_interesct_assoc = массив где ключи и значения одинаковы
    #array_diff_ukey($a, $b, 'compare');
}


$a = ['a' => 1, 'b' => 2, 'c' => 4, 'e' => 7];
$b = ['a' => 1, 'b' => 3, 'd' => 5, 'f' => 7];
$i =  array_intersect($a, $b); // a' => 1 не приводит к $newKeys если значения одинаковы 'a' => 1' e' => 7]
$i2 = array_intersect_assoc($a, $b); #a' => 1 значения и ключи не приводит к $newKeys
$i3 = array_intersect_key($a, $b);#'a' => 1, 'b' => 2 только ключи
#print_r($i3);
#появившися ключи
$newKeys = array_diff_key($b, $i3); #хочу +'d' => 5, +'f' => 7
#появившикся ключи и ключи с новыми значения (знак плюс) хочу 'b' => 3, +'d' => 5, +'f' => 7
    //ключи и их замененные значения: хочу 'b' => 3
    // или можно просто получить ключи который в двух массивах но значения в двух этих массивах должны бытьь разны

function my($data1, $data2)
{
    #var_dump($data1);
//новая идея 
    //находим пересекающиеся ключи например 'a', 'b'
    $keysInterection = array_keys(array_intersect_key($data1, $data2));
    $array = array_map(function ($jointKey) use ($data1, $data2) {
        if ($data1[$jointKey] === $data2[$jointKey]) {
            my($data1[$jointKey], $data2[$jointKey]);
        } else {
            #изменившиеся значения
            print_r("changed values\n");
            return ['key' => $jointKey, 'oldValue' => $data1[$jointKey], 'newValue' => $data2[$jointKey]];
        }}, $keysInterection);
        //$data1['a'] = 1 продолжается рекурсия
        //$data2['a'] = 1

        //$data1['b'] = 2 знак минус 
        //$data2['b'] = 3 знак плюс
    
    //ключи вне пересечений
    $keys1 = array_keys($data1);
    $keys2 = array_keys($data2);
    $dissappearedKeys = array_map(function ($key1, $key2) use ($keysInterection, $data1, $data2) {
        if (!in_array($key1, $keysInterection)) {
            return ['key' => $key1, 'status'  => 'delete', 'value'  => $data1[$key1]];
        }
        if (!in_array($key2, $keysInterection)) {
            return ['key' => $key2, 'status'  => 'new', 'value'  => $data2[$key2]];
        }

    }, $keys1, $keys2);  //'c' 'e' 

        //из data1 -
        //из data2 + 
    return ;
}
*/
function isAssociative($data) {
    if (is_array($data) && array_keys($data) !== range(0, count($data) -1)) {
        return true;
    }
    return false;
}
#var_dump($data1);
function hasChildren($data) {
    /*
    if (is_array($data)) {
        $value = array_values($data)[0];
        var_dump($value);
        if (isAssociative($value)){
            return true;
        }
    }
    return false;
    */
    //хочу чтобы вернула true если хоть одно значения элемента ассициативно
    return some($data, fn($pairs) => (isAssociative($pairs)));
    
}

$wow1 = ['wow'=>['so much' => 'lfn']];
$wow = ['www' => 'd', 'wow'=> ['so much' => 1]];
var_dump(hasChildren($wow));
#var_dump(count($wow));
#print_r(hasChildren($wow));
function compare($a, $b)
{
    if (!is_array($a) || !is_array($b)) {
        if ($a === $b) {
            return ['value' => $a, 'status' => 'unchanged'];
        }else {
            return ['oldValue' => $a, 'newValue' => $b];
        }
    }
    if ((!isAssociative($a)) || (!isAssociative($b))) {
        if ($a === $b) {
            return ['key' => array_keys($a), 'value' => array_values($b), 'status' => 'unchanged'];
        } else {
            return ['key' => array_keys($a), 'oldValue'=>array_values($a), 'newValue' => array_values($b)];
        }
    }
    $commonKeys = array_keys(array_intersect_key($a, $b));#from first array values
    
    $result = array_map(function ($key) use ($a, $b){
        return compare($a[$key], $b[$key]);
    }, $commonKeys, );
    #var_dump($result);

    
    $deletedData = array_diff_key($a, $commonKeys);
    return array_map(fn($data) => ['key' => array_keys($a), 'value' => array_values($a), 'status' => 'deleted'], $deletedData);
    #result собрать
    #array_map(function(), $commonKeys);
    
    #return $result;
}
compare($data1, $data2);

#print_r(compare($data1, $data2));
#исчезнувшие ключи = 'c' => 4, 'e' => 7
#$dissapeared = array_diff_key($a, $i3);
#print_r($dissapeared);
#compare($a, $b);




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
