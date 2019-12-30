<?php


namespace base\exceptions\database;


use Exception;

class SelectException extends Exception
{
    public function __construct()
    {
        parent::__construct("Ошибка при формировании запроса: переданный параметр не соответствует условиям!");
    }
}