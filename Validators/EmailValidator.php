<?php


namespace base\validators;


class EmailValidator
{
    private static $pattern = '/^(?P<name>(?:"?([^"]*)"?\s)?)(?:\s+)?(?:(?P<open><?)((?P<local>.+)@(?P<domain>[^>]+))(?P<close>>?))$/i';

    public static function validate($value)
    {
        var_dump(preg_match(self::$pattern, $value, $matches));

//        var_dump($matches);
    }
}