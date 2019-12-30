<?php

namespace base\database;

use base\App;
use \Exception;
use PDO;
use PDOException;


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
        try {
            $connection = new PDO("mysql:host=" . $mysql['host'] . ";dbname=" . $mysql['database'], $mysql['user'], $mysql['password']);
            $connection->query("set NAMES utf8");
        }
        catch (PDOException $e) {
            echo "Ошибка базы данных! [{$e->getCode()}]: {$e->getMessage()}";
            die();
        }

        return $connection;
    }

    public static function getConnection()
    {
        return self::getConnectionFromFile(CONFIG . "config.php");
    }

    public static function closeConnection($connection)
    {
        return $connection = null;
    }
}