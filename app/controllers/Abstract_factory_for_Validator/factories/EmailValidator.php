<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 08.02.2018
 * Time: 7:35
 */

namespace Email;

require_once 'app/controllers/Abstract_Factory_for_Validator/factories/AbstractFactory.php';
require_once 'app/controllers/Abstract_Factory_for_Validator/templates/MainTemplate.php';
require_once 'app/controllers/Abstract_Factory_for_Validator/traits/GeneralFunctions.php';

use Factories\AbstractFactory;
use MainTemplate\MainTemplate as MTem;


class EmailValidator extends AbstractFactory{

    private $array_of_params = null;
    private $email = null;
    private $pattern = null;
    private $MainTemplate = null;
    private $array_of_answers = array();

    use \GeneralFunctions\GeneralFunctions;

    private function CheckEmailWithPattern(array $pattern_as_array, array $values_as_array){
        //print_r($pattern_as_array); //delete
        //print_r($values_as_array); //delete
        //$array_of_answers = [];


        //проверяем совпадает ли количество опций с количеством значений в шаблоне
        //P.S проверка показала, что пустое значение преобразуется в нуль, что не есть хорошо.Этим нужно потом заняться
        if(count($pattern_as_array) != count($values_as_array)) die("Число опций и значений не совпадает!");

        if($pattern_as_array){
            //роверяем опции
            foreach($pattern_as_array as $i){
                switch($i){
                    case "r:": array_push($this->array_of_answers,$this->IsRequire($values_as_array[0]));
                        break;
                    case "min:": array_push($this->array_of_answers, $this->CheckMin($values_as_array[1], $this->email)); //сюда надо направлять данные из формы
                        break;
                    case "max:": array_push($this->array_of_answers, $this->CheckMax($values_as_array[2], $this->email)); //сюда надо направлять данные из формы
                        break;
                    case "spec:": //array_push($this->array_of_answers, $this->CheckSpecialChars($values_as_array[3],$this->email));
                        break;
                    case "specs:": array_push($this->array_of_answers, $this->CheckSpecialChars($values_as_array[4],
                        $this->email, $this->array_of_params));
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

    /*проверяем электронный адрес*/
    private function CheckEmail($value,$pattern){
        //print_r($value); //паттерн приходит правильным и значение тоже
        if(!$this->MainTemplate)
            $this->MainTemplate = new MTem();
        $answer = $this->MainTemplate->CheckData($value, $pattern); //
        //print_r($answer); //правильный массив значений опций. Удалить вывод
        //-------------------Буду проверять здесь сам емэйл--------------------------//

        //мне нужно рассмотреть каждую опцию шаблона и в соответствии с ней вызвать нужный метод из
        //трейта GeneralFunctions
        $options_as_array = $this->MainTemplate->GetTemplateAsArray(); // получил опции в шаблоне в виде массива
        //print_r($options_as_array); // delete
        $check_email = $this->CheckEmailWithPattern($options_as_array, $answer);

        return $check_email;
    }

    /*Должно возвращать 0 или 1. Проверяет шаблон*/
    protected function Check($pattern)
    {
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
    public function CheckData($value, $pattern = 0, $params = 0)
    {
        if($value && $pattern) {
            $this->email = $value;
            $this->pattern = $pattern;
            if ($params){
                $this->array_of_params = $params;
                //print_r($this->array_of_params);
            }
            $result = $this->Check($this->pattern); //проверяю паттерн на соответствие правилам
            if($result){
                $checkemail = $this->CheckEmail($this->email, $this->pattern);//здесь я проверяю валидность введенного мэйла
                return $checkemail;
            }
            return 0;


        }

        return 0;

    }

    /*Выдает результат проверки*/
    public function ResultOfCheck(){
        return $this->array_of_answers;
    }


} 