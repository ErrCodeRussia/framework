<?php


namespace base\exceptions\database;


use base\exceptions\BaseException;

class OffsetException extends BaseException
{
    public function __construct()
    {
        parent::__construct("Использование оператора OFFSET невозможно без LIMIT. Пожалуйста, укажите значение LIMIT!");
    }
}