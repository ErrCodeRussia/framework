<?php


namespace base\exceptions\config;


use base\exceptions\BaseException;

class ConfigException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Не задана константа CONFIG! Не удаётся получить доступ к файлам конфигурации!");
    }
}