<?php


namespace base\exceptions\config;


use Exception;
use Throwable;

class ConfigException extends Exception
{
    public function __construct()
    {
        parent::__construct("Не задана константа CONFIG! Не удаётся получить доступ к файлам конфигурации!");
    }
}