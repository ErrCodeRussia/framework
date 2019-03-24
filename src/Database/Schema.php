<?php

class Schema extends Requests
{

    public static function createTable($connection, $tableName, Closure $params)
    {
        $table = new Table();

        mysqli_query($connection, self::getCreateTableRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    public static function dropTableIfExist($connection, $tableName)
    {
        mysqli_query($connection, self::getDropTableRequest($tableName)) or die(mysqli_error($connection));
    }

    public static function addColumn($connection, $tableName, Closure $params)
    {
        $table = new Table();

        mysqli_query($connection, self::getAddColumnRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    public static function modifyColumn($connection, $tableName, Closure $params)
    {
        $table = new Table();

        mysqli_query($connection, self::getModifyColumnRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    public static function dropColumn($connection, $tableName, ... $columns)
    {
        foreach ($columns as $column) {
            mysqli_query($connection, self::getDropColumnRequest($tableName, $column)) or die(mysqli_error($connection));
        }
    }

    public static function setDefaultValue($connection, $tableName, $columnName, $value)
    {
        mysqli_query($connection, self::getDefaultValueRequest($tableName, $columnName, $value)) or die(mysqli_error($connection));
    }
}