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
     * @param string $dbname
     * @param Closure $params - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     */
    public static function createTable(string $tableName, Closure $params, $dbname = 'default')
    {
        $table = new SchemaTable();
        self::$database = new Database($dbname);

        $columns = $params->call($table, $table)->getColumns();

        self::$database->exec(SchemaRequests::getCreateTableRequest($tableName, $columns));
    }

    /**
     *  Удаление таблицы, если таковая существует
     *
     * @param string $tableName - название таблицы
     * @param string $dbname
     */
    public static function dropTableIfExist(string $tableName, $dbname = 'default')
    {
        self::$database = new Database($dbname);

        self::$database->query(SchemaRequests::getDropTableRequest($tableName));
    }

    /**
     *  Добавление колонки в существующую таблицы
     *
     * @param string $tableName - название таблицы
     * @param string $dbname
     * @param Closure $params - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     */
    public static function addColumn(string $tableName, Closure $params, $dbname = 'default')
    {
        $table = new SchemaTable();
        self::$database = new Database($dbname);

        $columns = $params->call($table, $table)->getColumns();

        self::$database->query(SchemaRequests::getAddColumnRequest($tableName, $columns));
    }

    /**
     *  Изменение существующей таблицы
     *
     * @param string $tableName - название таблицы
     * @param Closure $params - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     * @param string $dbname
     */
    public static function modifyColumn(string $tableName, Closure $params, $dbname = 'default')
    {
        $table = new SchemaTable();
        self::$database = new Database($dbname);

        $columns = $params->call($table, $table)->getColumns();

        self::$database->query(SchemaRequests::getModifyColumnRequest($tableName, $columns));
    }

    /**
     *  Удаление колонки, если таковая существует
     *
     * @param string $tableName - название таблицы
     * @param array $columns - массив названий колонок, которые нужно удалить
     * @param string $dbname
     */
    public static function dropColumn(string $tableName, array $columns, $dbname = 'default')
    {
        self::$database = new Database($dbname);

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
     * @param string $dbname
     */
    public static function setDefaultValue(string $tableName, string $columnName, $value, $dbname = 'default')
    {
        self::$database = new Database($dbname);

        self::$database->query(SchemaRequests::getDefaultValueRequest($tableName, $columnName, $value));
    }
}