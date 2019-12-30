<?php

namespace base\database\schema;

use base\database\Database;
use Closure;


class Schema
{
    private static $database;

    /**
     *  Создание таблицы в существующей базе данных
     *
     * @param string $tableName - название таблицы
     * @param Closure $params - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     */
    public static function createTable(string $tableName, Closure $params)
    {
        $table = new SchemaTable();
        self::$database = new Database();

        self::$database->query(SchemaRequests::getCreateTableRequest($tableName, $params->call($table, $table)));
    }

    /**
     *  Удаление таблицы, если таковая существует
     *
     * @param string $tableName - название таблицы
     */
    public static function dropTableIfExist(string $tableName)
    {
        self::$database = new Database();

        self::$database->query(SchemaRequests::getDropTableRequest($tableName));
    }

    /**
     *  Добавление колонки в существующую таблицы
     *
     * @param string $tableName - название таблицы
     * @param Closure $params - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     */
    public static function addColumn(string $tableName, Closure $params)
    {
        $table = new SchemaTable();
        self::$database = new Database();

        self::$database->query(SchemaRequests::getAddColumnRequest($tableName, $params->call($table, $table)));
    }

    /**
     *  Изменение существующей таблицы
     *
     * @param string $tableName - название таблицы
     * @param Closure $params   - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     * @param null $connection  - соединение с базой данных
     */
    public static function modifyColumn(string $tableName, Closure $params, $connection = null)
    {
        $table = new SchemaTable();
        self::$database = new Database();

        self::$database->query(SchemaRequests::getModifyColumnRequest($tableName, $params->call($table, $table)));
    }

    /**
     *  Удаление колонки, если таковая существует
     *
     * @param string $tableName - название таблицы
     * @param array $columns - массив названий колонок, которые нужно удалить
     */
    public static function dropColumn(string $tableName, array $columns)
    {
        self::$database = new Database();

        foreach ($columns as $column) {
            self::$database->query(SchemaRequests::getCreateTableRequest($tableName, $column));
        }
    }

    /**
     *  Установить значение по умолчанию для колонки
     *
     * @param string $tableName - название таблицы
     * @param string $columnName - название колонки
     * @param $value - значение по умолчанию
     */
    public static function setDefaultValue(string $tableName, string $columnName, $value)
    {
        self::$database = new Database();

        self::$database->query(SchemaRequests::getDefaultValueRequest($tableName, $columnName, $value));
    }
}