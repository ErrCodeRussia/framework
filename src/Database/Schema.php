<?php

class Schema extends Requests
{

    /**
     *  Создание таблицы в существующей базе данных
     *
     * @param string $tableName - название таблицы
     * @param Closure $params   - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     * @param null $connection  - соединение с базой данных
     */
    public static function createTable(string $tableName, Closure $params, $connection = null)
    {
        $table = new Table();
        if ($connection == null)
            $connection = DatabaseConnection::getConnection();

        mysqli_query($connection, self::getCreateTableRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    /**
     *  Удаление таблицы, если таковая существует
     *
     * @param string $tableName - название таблицы
     * @param null $connection  - соединение с базой данных
     */
    public static function dropTableIfExist(string $tableName, $connection = null)
    {
        if ($connection == null)
            $connection = DatabaseConnection::getConnection();

        mysqli_query($connection, self::getDropTableRequest($tableName)) or die(mysqli_error($connection));
    }

    /**
     *  Добавление колонки в существующую таблицы
     *
     * @param $connection       - соединение с базой данных
     * @param string $tableName - название таблицы
     * @param Closure $params   - результат выполнения анонимной функции вида function(Table $table) { ... return $table->getColumns(); }
     */
    public static function addColumn(string $tableName, Closure $params, $connection = null)
    {
        $table = new Table();
        if ($connection == null)
            $connection = DatabaseConnection::getConnection();

        mysqli_query($connection, self::getAddColumnRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
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
        $table = new Table();
        if ($connection == null)
            $connection = DatabaseConnection::getConnection();

        mysqli_query($connection, self::getModifyColumnRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    /**
     *  Удаление колонки, если таковая существует
     *
     * @param string $tableName - название таблицы
     * @param array $columns    - массив названий колонок, которые нужно удалить
     * @param null $connection  - соединение с базой данных
     */
    public static function dropColumn(string $tableName, array $columns, $connection = null)
    {
        if ($connection == null)
            $connection = DatabaseConnection::getConnection();

        foreach ($columns as $column) {
            mysqli_query($connection, self::getDropColumnRequest($tableName, $column)) or die(mysqli_error($connection));
        }
    }

    /**
     *  Установить значение по умолчанию для колонки
     *
     * @param string $tableName     - название таблицы
     * @param string $columnName    - название колонки
     * @param $value                - значение по умолчанию
     * @param null $connection      - соединение с базой данных
     */
    public static function setDefaultValue(string $tableName, string $columnName, $value, $connection = null)
    {
        if ($connection == null)
            $connection = DatabaseConnection::getConnection();

        mysqli_query($connection, self::getDefaultValueRequest($tableName, $columnName, $value)) or die(mysqli_error($connection));
    }
}