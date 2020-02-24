<?php


namespace base\exceptions\database;


use Exception;

class SelectArrayException extends Exception
{
    public function __construct()
    {
        parent::__construct("Ошибка при формировании запроса: передан пустой массив!");
    }
}