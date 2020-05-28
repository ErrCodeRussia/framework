<?php


namespace base\Validators;


class Validator
{
    private $validators = [
        'email' => 'base\Validators\EmailValidator'
    ];

    public function email($value)
    {
        return $this->validators['email']::validate($value);
    }
}