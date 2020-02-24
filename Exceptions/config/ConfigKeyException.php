<?php


namespace base\exceptions\config;


use Exception;

class ConfigKeyException extends Exception
{
    public function __construct($key)
    {
        parent::__construct("Обнаружено неизвестное свойство: [{$key}]. Обработка невозможма!");
    }
}