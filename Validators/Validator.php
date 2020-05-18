<?php


namespace base\validators;


class Validator
{
    private $validators = [
        'email' => 'base\validators\EmailValidator'
    ];

    public function email($value)
    {
        return $this->validators['email']::validate($value);
    }
}