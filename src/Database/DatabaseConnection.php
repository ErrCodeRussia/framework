<?php

class DatabaseConnection implements Connection
{

    /**
     *  Получение соединения с базой данных с помощью ввода данных для соединения
     *
     * @param string $server    - адрес сервера (например, 127.0.0.1)
     * @param string $user      - имя пользователя
     * @param string $password  - пароль пользователя
     * @param string $database  - название базы данных
     *
     * @return mixed|mysqli     - соединение с базой данных
     */
    public static function getConnectionFromData(string $server, string $user, string $password, string $database)
    {
        $mysql['server'] = $server;
        $mysql['user'] = $user;
        $mysql['password'] = $password;
        $mysql['database'] = $database;

        return self::getMysqliConnection($mysql);
    }

    /**
     *  Получение соединения с базой данных с помощью .ini-файла
     *
     * @param string $file  - путь до .ini-файла, имеющего вид:
     *  [database]
     *  server   = server_address
     *  user     = user_name
     *  password = user_password
     *  database = database_name
     *
     * @return mixed|mysqli - соединение с базой данных
     */
    public static function getConnectionFromFile(string $file)
    {
        if (file_exists($file)) {
            $config = parse_ini_file($file, true);

            try {
                if (!is_null($config)) {
                    $mysql['server'] = $config['database']['server'];
                    $mysql['user'] = $config['database']['user'];
                    $mysql['password'] = $config['database']['password'];
                    $mysql['database'] = $config['database']['database'];

                    return self::getMysqliConnection($mysql);
                }
                else {
                    throw new Exception("Failed to open your .ini-file!");
                }
            }
            catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     *  Получение соединения с базой данных для ErrCode Engine
     *
     * @return mysqli
     */
    public static function getConnection()
    {
        $file = CONFIG . ".ini";

        if (file_exists($file)) {
            $config = parse_ini_file($file, true);

            try {
                if (!is_null($config)) {
                    $mysql['server'] = $config['database']['server'];
                    $mysql['user'] = $config['database']['user'];
                    $mysql['password'] = $config['database']['password'];
                    $mysql['database'] = $config['database']['database'];

                    return self::getMysqliConnection($mysql);
                }
                else {
                    throw new Exception("Failed to open .ini-file!");
                }
            }
            catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    /**
     *  Получение соединения, исходя из полученных данных
     *
     * @param array $mysql  - массив вида ['server' => 'value', 'user' => 'value', 'password' => 'value', 'database' => 'value']
     *
     * @return mysqli       - соединение с базой данных
     */
    private static function getMysqliConnection(array $mysql)
    {
        $connection = mysqli_connect(
            $mysql['server'],
            $mysql['user'],
            $mysql['password'],
            $mysql['database']
        );
        mysqli_query($connection, "set NAMES utf8");

        return $connection;
    }
}