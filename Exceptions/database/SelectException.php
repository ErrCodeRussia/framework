<?php


namespace base\exceptions\database;


use base\exceptions\BaseException;

class SelectException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Ошибка при формировании запроса: переданный параметр не соответствует условиям!");
    }
}