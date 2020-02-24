<?php


namespace base\exceptions\config;


use Exception;
use Throwable;

class ConfigFileException extends Exception
{
    public function __construct()
    {
        parent::__construct("Не удаётся найти файл конфигурации сайта!");
    }
}