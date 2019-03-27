<?php

class Schema extends Requests
{

    /**
     *  Создание таблицы в существующей базе данных
     *
     * @param $connection       - соединение с базой данных
     * @param string $tableName - название таблицы
     * @param Closure $params   - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     */
    public static function createTable($connection, string $tableName, Closure $params)
    {
        $table = new Table();

        mysqli_query($connection, self::getCreateTableRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    /**
     *  Удаление таблицы, если таковая существует
     *
     * @param $connection       - соединение с базой данных
     * @param string $tableName - название таблицы
     */
    public static function dropTableIfExist($connection, string $tableName)
    {
        mysqli_query($connection, self::getDropTableRequest($tableName)) or die(mysqli_error($connection));
    }

    /**
     *  Добавление колонки в существующую таблицы
     *
     * @param $connection       - соединение с базой данных
     * @param string $tableName - название таблицы
     * @param Closure $params   - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     */
    public static function addColumn($connection, string $tableName, Closure $params)
    {
        $table = new Table();

        mysqli_query($connection, self::getAddColumnRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    /**
     *  Изменение существующей таблицы
     *
     * @param $connection       - соединение с базой данных
     * @param string $tableName - название таблицы
     * @param Closure $params   - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     */
    public static function modifyColumn($connection, string $tableName, Closure $params)
    {
        $table = new Table();

        mysqli_query($connection, self::getModifyColumnRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    /**
     *  Удаление колонки, если таковая существует
     *
     * @param $connection       - соединение с базой данных
     * @param string $tableName - название таблицы
     * @param mixed ...$columns - название колонок, которые нужно удалить
     */
    public static function dropColumn($connection, string $tableName, ... $columns)
    {
        foreach ($columns as $column) {
            mysqli_query($connection, self::getDropColumnRequest($tableName, $column)) or die(mysqli_error($connection));
        }
    }

    /**
     *  Установить значение по умолчанию для колонки
     *
     * @param $connection           - соединение с базой данных
     * @param string $tableName     - название таблицы
     * @param string $columnName    - название колонки
     * @param $value                - значение по умолчанию
     */
    public static function setDefaultValue($connection, string $tableName, string $columnName, $value)
    {
        mysqli_query($connection, self::getDefaultValueRequest($tableName, $columnName, $value)) or die(mysqli_error($connection));
    }
}