<?php

namespace base\database;

use base\App;
use base\config\Config;
use \Exception;
use PDO;
use PDOException;


class Connection
{
    public static function getConnection($dbname = null)
    {
        if (isset(App::$config)) {
            App::$config = new Config();
        }

        if (is_null($dbname))
            $database = App::$config->database['default'];
        else
            $database = App::$config->database[$dbname];

        try {
            if (!is_null($database)) {
                $mysql['host'] = $database['host'];
                $mysql['user'] = $database['user'];
                $mysql['password'] = $database['password'];
                $mysql['database'] = $database['database'];
                $mysql['port'] = (isset($database['port'])) ? $database['port'] : null;

                return self::getMysqliConnection($mysql);
            }
            else {
                throw new Exception("Ошибка при чтении настроек базы данных!");
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function closeConnection($connection)
    {
        return $connection = null;
    }

    private static function getMysqliConnection(array $mysql)
    {
        try {
            $settings = "mysql:host=" . $mysql['host'];
            if (!is_null($mysql['port'])) {
                $settings .= ";port=" . $mysql['port'];
            }
            $settings .= ";dbname=" . $mysql['database'];

            $connection = new PDO($settings, $mysql['user'], $mysql['password']);
            $connection->query("set NAMES utf8");
        }
        catch (PDOException $e) {
            echo "Ошибка базы данных! [{$e->getCode()}]: {$e->getMessage()}";
            die();
        }

        return $connection;
    }
}