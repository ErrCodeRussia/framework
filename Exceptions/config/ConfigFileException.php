<?php


namespace base\exceptions\config;


use base\exceptions\BaseException;

class ConfigFileException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Не удаётся найти файл конфигурации сайта!");
    }
}