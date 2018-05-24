<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 08.02.2018
 * Time: 7:15
 */
require_once 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('app/views/html');
$twig = new Twig_Environment($loader, array(
    'cache' => '/path/to/compilation_cache',
    'auto_reload' => true
));

echo $twig->render('index.html', array('name' => 'Vlad'));
