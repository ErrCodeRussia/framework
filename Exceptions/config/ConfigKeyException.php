<?php


namespace base\exceptions\config;


use base\exceptions\BaseException;

class ConfigKeyException extends BaseException
{
    public function __construct($key)
    {
        parent::__construct("Обнаружено неизвестное свойство: [{$key}]. Обработка невозможма!");
    }
}