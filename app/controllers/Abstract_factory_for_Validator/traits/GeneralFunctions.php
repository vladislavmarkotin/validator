<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 18.02.2018
 * Time: 6:51
 */

namespace GeneralFunctions;

/*
 * Трейт, содержащий основные проверки входящих значений
 */
trait GeneralFunctions {

    //Проверяет переданное значение на существование
    public function IsRequire($value){

        if($value)
            return true;
        return false;

    }

    /*
     * @param String $arg Строка, которая не должна быть пустой
     * @return Boolean
     */
    public function IsNotEmpty($arg){
        if(strlen($arg)){
            return true;
        }
        return false;
    }

    /*
     * Проверяет чтобы входящий параметр не был короче з
     * @param String $arg строка для проверки
     * @param  Integer $value значение из шаблона
     * @return Boolean
     */
    public function CheckMin($value, $arg){
        if(strlen($arg) >= $value)
            return true;
        return false;
    }

    /*
     * Проверяет чтобы входящий параметр не был больше заданного значения
     * @param String $arg строка для проверки
     * @param  Integer $value значение из шаблона
     * @return Boolean
     */
    public function CheckMax($max, $arg){
        if(strlen($arg) < $max)
            return true;
        return false;
    }

    /*
     * Проверяет наличие обязательных символов в переданном значении
     * @param  array $values Обязательные символы
     * @param mixed $arg аргумент для проверки
     */
    public function CheckSpecialChars($values, $arg, $params = 0){

        if(is_array($params)){
            $specs_arr = $params['specchars'];

            foreach($specs_arr as $i){
                if(stripos($arg, $i) === false)
                {
                    return false;
                }

            }
            return true;
        }
        else{
            return stripos($arg, $values);
        }
    }

} 