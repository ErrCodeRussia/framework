<?php


namespace base\exceptions\database;


use base\exceptions\BaseException;

class SelectArrayException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Ошибка при формировании запроса: передан пустой массив!");
    }
}