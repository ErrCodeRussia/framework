<?php


namespace base\exceptions\database;


use Exception;

class WrongValueException extends Exception
{
    public function __construct($value)
    {
        parent::__construct("Значение '" . $value . "' недопустимо в этой позиции!");
    }
}