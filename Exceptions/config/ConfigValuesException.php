<?php


namespace base\exceptions\config;


use Exception;

class ConfigValuesException extends Exception
{
    public function __construct($key, $value)
    {
        parent::__construct("Ошибка при добавлении свойства [{$key}] со значением [{$value}]. Значение не добавлено!");
    }
}