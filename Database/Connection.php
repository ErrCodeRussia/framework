<?php

namespace base\database;

use base\App;
use \Exception;


class Connection
{
    public static function getConnectionFromFile($file)
    {
        if (file_exists($file)) {
            $database = App::$config->database;

            try {
                if (!is_null($database)) {
                    $mysql['host'] = $database['host'];
                    $mysql['user'] = $database['user'];
                    $mysql['password'] = $database['password'];
                    $mysql['database'] = $database['database'];

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
    }

    private static function getMysqliConnection(array $mysql)
    {
        $connection = mysqli_connect(
            $mysql['host'],
            $mysql['user'],
            $mysql['password'],
            $mysql['database']
        ) or die (mysqli_connect_error());
        mysqli_query($connection, "set NAMES utf8");

        return $connection;
    }

    public static function getConnection()
    {
        return self::getConnectionFromFile(CONFIG . "config.ini");
    }

    public static function closeConnection($connection)
    {
        return mysqli_close($connection);
    }
}