<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 05.01.2018
 * Time: 7:09
 */

/*
 * Стартовая страница валидатора. Здесь валидатор получает входные данные.Также здесь задаются массивы с паттернами для
 * проверки входящих данных.Паттерны находятся в специальных массивах, которые состоят из типа валидатора,который нужно
 * применить, самого шаблона проверки, а также массива специальных символов, которые должны быть в передаваемых данных.
 * Заранее могу сказать, что подобная идея была заимствована у фреймворка Laravel. Реализована просто из желания
 * потренироваться и написать нечто отдаленно напоминающее. Я знаю про filter_var(), но хотелось написать что-то свое.
 * Плюс, хотелось потренироваться в проектировании чего-то, что сложнее hello world :)
 *
 */
namespace Main;
require_once 'config.php'; //подкючает файл, где находится автозагрузка twig'а через composer
require_once 'app/controllers/Abstract_factory_for_Validator/FactoryManager.php';

use Manager\FactoryManager as Manager;


/*
 * Данный массив описывает как нужно проверять входящий электронный адрес. Так аргумент "type" говорит о том, что за
 * аргумент сюда придет. Аргумент "pattern" - это шаблон, по которому будет проверяться входное значение.Подробнее об
 * опциях в строке шаблона будет рассказано в файле MainTrait.php. Подмассив "specchars" неразрывно связан с опцикй
 * specs в шаблоне.Данная опция, выставленная в единицу,говорит о том,что присутствует массив specchars и в нем
 * находятся символы, которые обязательно должны быть в переданном на проверку параметре. Если не указать specs:1 в
 * шаблоне, то массив специальных символов specchars будет проигнорирован
 */
$params = [
    "type" => "email",
    "pattern" => 'r:1;min:5;max:30;specs:1;',
    "specchars" => ["@",".","com"],
];

$params_for_pass = [
    "type" => "pass",
    "pattern" => 'r:1;min:6;max:100;',
];

$params_for_name = [
    "type" => "text",
    "pattern" => "r:1;min:2;max:32;",
    //"specchars" => ["@"]. Просто для проверки. Если раскомментировать эту строку и добавить specs:1 в паттерн, то
    //аргумент будет обязательно проверяться на наличие символа @
];

if($_POST){
    //получение данных
    $params['data'] = $_POST['email'];
    $params_for_pass['data'] = $_POST['pass'];
    $params_for_name['data'] = $_POST['name'];

    //создаем обект менеджера, необходимого для начала проверки
    //менеджер создается с помощью паттерна singleton
    $manager = Manager::getInstance();
    //создание фабрики, которая проверит поступившие данные
    $result = $manager->CreateFactory($params);
    $result_for_pass = $manager->CreateFactory($params_for_pass);
    $result_for_name = $manager->CreateFactory($params_for_name);

    /*
     * Здесь проверяются результаты проверки и выводится соответсвующее сообщение
     */
    if($result_for_name && $result && $result_for_pass)
        echo "<h1>Your Input is correct!</h1>";
    else echo "<h1> There was at least one mistake</h1>";

    /*
     * Отладочный вывод. Здесь можно увидеть какой именно аргумент не прошел проверку
     */
    print_r("Result of email check: ".$result);
    print_r(" Result of pass check: ".$result_for_pass);
    print_r(" Result for text: ".$result_for_name);
}






