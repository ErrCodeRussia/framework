<?php


namespace base\exceptions\database;


use base\exceptions\BaseException;

class WrongValueException extends BaseException
{
    public function __construct($value)
    {
        parent::__construct("Значение '" . $value . "' недопустимо в этой позиции!");
    }
}