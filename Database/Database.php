<?php

namespace base\database;


use base\security\Security;

class Database
{
    private $connection;

    /**
     *  Database constructor.
     * @param string $dbname
     */
    public function __construct($dbname)
    {
        $this->connection = Connection::getConnection($dbname);
    }

    /**
     *  Database destructor
     */
    public function __destruct()
    {
        Connection::closeConnection($this->connection);
    }


    /**
     *  Получение массива данных по соединению и SQL-запросу
     *
     * @param $sql              - запрос к базе даных
     * @param bool|null $decode - флаг для декодирования строк
     * @return array            - массив данных или errorInfo
     */
    public function getQueryArray($sql, $decode = null)
    {
        $res = array();

        $query = $this->connection->query($sql);

        if (!is_array($query)) {
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            while ($arr = $query->fetch()) {

                if ($decode) {

                    $array = array();
                    foreach ($arr as $key => $item) {
                        $array[$key] = Security::aps_decode($item);
                    }

                    $res[] = $array;
                }
                else {
                    $res[] = $arr;
                }
            }

            return $res;
        }
        else {
            return $query;
        }
    }

    public function exec($sql)
    {
        $exec = $this->connection->exec($sql);

        if ($exec) {
            return $exec;
        }
        else {
            return $this->connection->errorInfo();
        }
    }

    public function query($sql)
    {
        $query = $this->connection->query($sql);

        if ($query) {
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            return $query;
        }
        else {
            return $this->connection->errorInfo();
        }
    }

    public function getInsertId()
    {
        return $this->connection->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    public function commit()
    {
        return $this->connection->commit();
    }

    public function rollBack()
    {
        return $this->connection->rollBack();
    }
}