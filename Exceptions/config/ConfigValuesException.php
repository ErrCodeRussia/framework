<?php


namespace base\exceptions\config;


use base\exceptions\BaseException;

class ConfigValuesException extends BaseException
{
    public function __construct($key, $value)
    {
        parent::__construct("Ошибка при добавлении свойства [{$key}] со значением [{$value}]. Значение не добавлено!");
    }
}