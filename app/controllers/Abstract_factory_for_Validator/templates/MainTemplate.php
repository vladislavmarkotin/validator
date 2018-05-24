<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 10.02.2018
 * Time: 11:24
 */

namespace MainTemplate;

require_once 'app/controllers/Abstract_Factory_for_Validator/templates/AbstractTemplate.php';
require_once 'app/controllers/Abstract_Factory_for_Validator/traits/MainTrait.php';

use AbstractTemplate\AbstractTemplate as AT;
use MainTrait\MainTrait;


class MainTemplate extends AT{

    private $template_as_array = null;

    use MainTrait;

    /* Проверяем шаблон */
    public function CheckTemplate($template){
        //возвращает массив опций
        if(is_string($template))
        {
            $answers = [];
            $answer = $this->IsCorrect($template); //вернули массив опций
            if($answer){
                $real_options = $this->GetOptions($answer); //получаю массив опций
                $is_options_right = $this->IsOptionCorrect($real_options);
                //не хватает проверки!
                array_push($answers, $is_options_right);
                $this->template_as_array = $real_options;
            }
            return $answers;
        }
        return 0;

    }

    //возвращает либо 0 - ошибка,лиюо 1 - все верно
    public function CheckData($value, $pattern){
        //echo " MainTemplate Check Data $value";

        $answers = [];
        $values = $this->GetValues($pattern);
        //print_r(" ".$value." "); //delete true
        if($values){
            $right_values = $this->IsValueCorrect($values); //преобразовал здесь строки из паттерна в соответствющие типы
            //print_r($right_values); //true
            $answers = $right_values;
            //было бы очень здорово поставить в качестве индексов в массиве $answers опции из шаблона
            return $answers;
        }
        return 0;
    }

    public function GetTemplateAsArray(){
        if($this->template_as_array)
            return $this->template_as_array;

        return 0;
    }
} 