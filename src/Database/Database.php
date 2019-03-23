<?php

class Database
{
    private $connection;
    private $mysql = array(
        'server' => '127.0.0.1',
        'user' => 'mysql',
        'password' => 'mysql',
        'database' => ''
    );

    public function __construct()
    {
        if (file_exists(CONFIG . '.ini')) {
            $config = parse_ini_file(CONFIG . ".ini", true);

            if (!is_null($config)) {
                $this->mysql['server'] = $config['database']['server'];
                $this->mysql['user'] = $config['database']['user'];
                $this->mysql['password'] = $config['database']['password'];
                $this->mysql['database'] = $config['database']['database'];
            }

            $this->setConnection();
        }
    }

    private function setConnection()
    {
        $this->connection = mysqli_connect(
            $this->mysql['server'],
            $this->mysql['user'],
            $this->mysql['password'],
            $this->mysql['database']
        );

        mysqli_query($this->connection, "set NAMES utf8");
    }

    public function getConnection()
    {
        return $this->connection;
    }
}