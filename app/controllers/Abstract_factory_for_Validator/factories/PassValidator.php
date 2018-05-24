<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 28.02.2018
 * Time: 8:26
 */

namespace Pass;

require_once 'app/controllers/Abstract_Factory_for_Validator/factories/AbstractFactory.php';
require_once 'app/controllers/Abstract_Factory_for_Validator/templates/MainTemplate.php';
require_once 'app/controllers/Abstract_Factory_for_Validator/traits/GeneralFunctions.php';

use Factories\AbstractFactory;
use MainTemplate\MainTemplate as MTem;


class PassValidator extends AbstractFactory{

    private $array_of_params = null;
    private $pass = null;
    private $pattern = null;
    private $MainTemplate = null;
    private $array_of_answers = array();

    use \GeneralFunctions\GeneralFunctions;

    private function CheckPassWithPattern(array $pattern_as_array, array $values_as_array){
        if(count($pattern_as_array) != count($values_as_array)) die("Число опций и значений не совпадает!");

        if($pattern_as_array){
            //роверяем опции
            //print_r($pattern_as_array); //true
            foreach($pattern_as_array as $i){
                switch($i){
                    case "r:": array_push($this->array_of_answers,$this->IsRequire($values_as_array[0]));
                        break;
                    case "min:": array_push($this->array_of_answers, $this->CheckMin($values_as_array[1], $this->pass)); //сюда надо направлять данные из формы
                        break;
                    case "max:": array_push($this->array_of_answers, $this->CheckMax($values_as_array[2], $this->pass)); //сюда надо направлять данные из формы
                        break;
                    case "specs:": array_push($this->array_of_answers, $this->CheckSpecialChars($values_as_array[3],
                        $this->pass, $this->array_of_params));
                        break;
                    default:
                        die("Опция не распознана");
                }

            }
        }

        //print_r($this->array_of_answers); //Это самая интересная и важная информация.Она показывает при какой проверке возникла ошибка

        foreach($this->array_of_answers as $i){
            if(!$i)
                return false;
        }

        return true;
    }

    /*проверяем пароль*/
    private function CheckPass($value, $pattern){
        //print_r($pattern); //паттерн приходит правильным и значение тоже
        if(!$this->MainTemplate)
            $this->MainTemplate = new MTem();
        $answer = $this->MainTemplate->CheckData($value, $pattern); //
        //print_r($answer); //правильный массив значений опций. Удалить вывод
        //-------------------Буду проверять здесь сам емэйл--------------------------//

        //мне нужно рассмотреть каждую опцию шаблона и в соответствии с ней вызвать нужный метод из
        //трейта GeneralFunctions
        $options_as_array = $this->MainTemplate->GetTemplateAsArray(); // получил опции в шаблоне в виде массива
        //print_r($options_as_array); // delete
        $check_pass = $this->CheckPassWithPattern($options_as_array, $answer);

        return $check_pass;
    }

    /*Должно возвращать 0 или 1. Проверяет шаблон*/
    protected function Check($pattern){
        if(!$this->MainTemplate)
            $this->MainTemplate = new MTem();
        $answer = $this->MainTemplate->CheckTemplate($pattern);
        foreach($answer as $a){
            if(!$a)
                return 0;
        }
        return 1;
    }

    /*Главная точка, откуда начинаются проверки*/
    public function CheckData($value, $pattern = 0, $params = 0){

        if($value && $pattern){
            $this->pass = $value;
            $this->pattern = $pattern;

            if ($params){
                $this->array_of_params = $params;
                //print_r($this->array_of_params);
            }

            $result = $this->Check($this->pattern); //проверяю паттерн на соответствие правилам

            //print_r("Pattern:".$result); //Результат проверки паттерна

            if($result){
                $checkpass = $this->CheckPass($this->pass, $this->pattern);//здесь я проверяю валидность введенного пароля
                //var_dump($checkpass);
                return $checkpass;
            }
            return 0;
        }

        return 0;
    }

    public function ResultOfCheck(){
        return $this->array_of_answers;
    }

} 