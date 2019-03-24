<?php

interface Connection
{
    /**
     * @param string $server    - адрес сервера (например, 127.0.0.1)
     * @param string $user      - имя пользователя
     * @param string $password  - пароль пользователя
     * @param string $database  - название базы данных
     *
     * @return mixed
     */
    public static function getConnectionFromData(string $server, string $user, string $password, string $database);

    /**
     * @param string $file  - путь до .ini-файла, имеющего вид:
     *  [database]
     *  server = server_address
     *  user = user_name
     *  password = user_password
     *  database = database_name
     *
     * @return mixed
     */
    public static function getConnectionFromFile(string $file);
}