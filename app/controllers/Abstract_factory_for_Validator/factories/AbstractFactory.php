<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 08.02.2018
 * Time: 7:22
 */
namespace Factories;

abstract class AbstractFactory {
    abstract protected function CheckData($value, $pattern = 0);

    abstract protected function Check($pattern);
} 