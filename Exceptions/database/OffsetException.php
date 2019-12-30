<?php


namespace base\exceptions\database;


use Exception;
use Throwable;

class OffsetException extends Exception
{
    public function __construct()
    {
        parent::__construct("Использование оператора OFFSET невозможно без LIMIT. Пожалуйста, укажите значение LIMIT!");
    }
}