<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 10.02.2018
 * Time: 11:08
 */

namespace MainTrait;

use Twig\Error\Error;

trait MainTrait {

    /*Массив опций, который используется в шаблонах
    *Если есть желание расширить шаблон новыми опциями их прежде всего
    * надо добавить сюда
    */
    private $options = [
        "r:",
        "min:",
        "max:",
        "spec:",
        "specs:"
    ];

    /* Массив типов значений в шаблоне.
     * При проверке опций в шаблоне также проверяются и их типы
     */
    private $types_of_values = [
        "r:" => "boolean",
        "min:" => "int",
        "max:" => "int",
        "spec" => "array",
        "specs:" => "int",
    ];

    /*
     * Здесь хранится шаблон для проверка как строка
     */
    private $template_as_array = null;

    /* Выделяем правильные опции из массива опций из шаблона */
    function GetOptions(array $options){
        //проверяем опции,которые в шаблоне

        $real_options = [];
        foreach ($options as $i) {
            if($find = strripos($i, ":")){
                //echo " $find ";
                $o = substr($i, 0, $find + 1);
                array_push($real_options, $o);
            }
        }
        //print_r($real_options); //delete
        return $real_options;
    }

    /*Выделяем значения опций в шаблоне*/
    function GetValues($template){
        $template_as_array = explode(";",$template);
        $real_values = [];
        /* Мне нужно выделить значения из массива опций $template_as_array  */
        foreach ($template_as_array as $i) {
            $pos_of_delimetr = strripos($i, ":");
            if($i)
                array_push($real_values,substr($i, $pos_of_delimetr  + 1));
        }
        //print_r($real_values);
        return $real_values;
    }

    /* Меняем тип значения из шаблона на соответствующий*/
    function IsValueCorrect(array $values){
        $true_types = [];
        $i = 0;
        foreach($values as $v){
            try{
                switch($i){
                    case 0: $true_types[$i] = (bool)$v;
                            $i++;
                        break;
                    case 1:
                        $true_types[$i] = (int)$values[$i];
                        $i++;
                        break;
                    case 2:
                        $true_types[$i] = (int)$v;
                        $i++;
                        break;
                    case 3:
                        $true_types[$i] = (string)$v;
                        $i++;
                        break;
                    case 4:
                        $v = (bool)$v;
                        $true_types[$i] = (bool)$v; // вот здесь мне надо как-то проверить $v и если оно true,то
                        //перейти к массиву specs
                        if($v){

                        }
                        break;
                    default:
                        die("Error in types!!");
                }
            }catch (Error $e){
                die("Error in types!");
            }
        }

        return $true_types; // массив
    }

    /*Проверряем корректность заданных опций*/
    function IsOptionCorrect(array $real_options){
        $length_of_array = count($real_options);
        $flag = 0;

        /*сравниваем опции из шаблона с реальными */
        foreach($real_options as $o){
            foreach($this->options as $i){
                if($o != $i) continue;
                else{
                    $flag++;
                    break;
                }
            }
        }

        if($length_of_array == $flag)
            return 1;
        return 0;
    }

    //надо разбить строку параметров на опции валидатора
    function IsCorrect($template){
        $template_as_array = explode(";", $template);

        if($template_as_array)
            return $template_as_array;
        return 0;
    }
} 