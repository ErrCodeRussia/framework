<?php

namespace base\security;

class Security
{
    private static $emailRegExp = "/.+@.+\..+/";
    private static $phoneRegExp = "/^((8|\+7|7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{10,12}$/";

    /**
     * @return string
     */
    public static function getEmailRegExp()
    {
        return self::$emailRegExp;
    }

    /**
     * @return string
     */
    public static function getPhoneRegExp()
    {
        return self::$phoneRegExp;
    }

    public static function protectData($data)
    {
        $protectData = array();

        foreach ($data as $key => $value) {
            $protectData[$key] = self::replace_aps(htmlspecialchars(trim($value)));
        }

        return $protectData;
    }

    public static function replace_aps($string)
    {
        $string = str_replace("'", "&aps", $string);

        return $string;
    }
}