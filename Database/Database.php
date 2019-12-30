<?php

namespace base\database;


class Database
{
    private $connection;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->connection = Connection::getConnection();
    }


    /**
     *  Получение массива данных по соединению и SQL-запросу
     *
     * @param $sql - запрос к базе даных
     * @return array|null   - массив данных
     */
    public function getQueryArray($sql)
    {
        $res = array();

        if ($query = $this->connection->query($sql)) {
            $query->setFetchMode(\PDO::FETCH_ASSOC);
            while ($arr = $query->fetch()) {
                $res[] = $arr;
            }

            return $res;
        }
        else
            return null;
    }

    public function exec($sql)
    {
        return $this->connection->exec($sql);
    }

    public function query($sql)
    {
        $query = $this->connection->query($sql);
        $query->setFetchMode(\PDO::FETCH_ASSOC);

        return $query;
    }

    public function getInsertId()
    {
        return $this->connection->lastInsertId();
    }
}