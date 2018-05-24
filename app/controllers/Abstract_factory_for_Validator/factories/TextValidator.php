<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 08.03.2018
 * Time: 6:34
 */

namespace Text;

require_once 'app/controllers/Abstract_Factory_for_Validator/factories/AbstractFactory.php';
require_once 'app/controllers/Abstract_Factory_for_Validator/templates/MainTemplate.php';
require_once 'app/controllers/Abstract_Factory_for_Validator/traits/GeneralFunctions.php';

use Factories\AbstractFactory as AF;
use MainTemplate\MainTemplate as MTem;

class TextValidator extends AF{

    private $array_of_params = null;
    private $name = null;
    private $pattern = null;
    private $MainTemplate = null;
    private $array_of_answers = array();

    use \GeneralFunctions\GeneralFunctions;

    private function CheckNameWithPattern(array $pattern_as_array, array $values_as_array){
        if(count($pattern_as_array) != count($values_as_array)) die("Число опций и значений не совпадает!");

        if($pattern_as_array){
            foreach($pattern_as_array as $i){
                switch($i){
                    case "r:": array_push($this->array_of_answers,$this->IsRequire($values_as_array[0]));
                        break;
                    case "min:": array_push($this->array_of_answers, $this->CheckMin($values_as_array[1], $this->name)); //сюда надо направлять данные из формы
                        break;
                    case "max:": array_push($this->array_of_answers, $this->CheckMax($values_as_array[2], $this->name)); //сюда надо направлять данные из формы
                        break;
                    case "specs:": array_push($this->array_of_answers, $this->CheckSpecialChars($values_as_array[3],
                        $this->name, $this->array_of_params));
                        break;

                    default:
                        die("Опция не распознана");
                }

            }
        }

        foreach($this->array_of_answers as $i){
            if(!$i)
                return false;
        }

        return true;
    }

    /*проверяем пароль*/
    private function CheckName($value, $pattern){
        //print_r($pattern); //паттерн приходит правильным и значение тоже
        if(!$this->MainTemplate)
            $this->MainTemplate = new MTem();
        $answer = $this->MainTemplate->CheckData($value, $pattern); //

        $options_as_array = $this->MainTemplate->GetTemplateAsArray(); // получил опции в шаблоне в виде массива
        $check_name = $this->CheckNameWithPattern($options_as_array, $answer);

        return $check_name;
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
            $this->name = $value;
            $this->pattern = $pattern;

            if ($params){
                $this->array_of_params = $params;
                //print_r($this->array_of_params);
            }

            $result = $this->Check($this->pattern); //проверяю паттерн на соответствие правилам

            //print_r("Pattern:".$result); //Результат проверки паттерна. All right

            if($result){
                $checkname = $this->CheckName($this->name, $this->pattern);//здесь я проверяю валидность введенного имени
                //var_dump($checkname);
                return $checkname;
            }
            return 0;
        }

        return 0;
    }

    public function ResultOfCheck(){
        return $this->array_of_answers;
    }
} 