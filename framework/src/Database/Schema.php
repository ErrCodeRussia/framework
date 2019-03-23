<?php

class Schema extends Requests
{

    public static function createTable($tableName, Closure $params)
    {
        $table = new Table();
        $connection = Requests::getConnection();

        mysqli_query($connection, self::getCreateTableRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    public static function dropTableIfExist($tableName)
    {
        $connection = Requests::getConnection();

        mysqli_query($connection, "DROP TABLE IF EXISTS `$tableName`") or die(mysqli_error($connection));
    }

    public static function addColumn($tableName, Closure $params)
    {
        $table = new Table();
        $connection = Requests::getConnection();

        mysqli_query($connection, self::getAddColumnRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    public static function modifyColumn($tableName, Closure $params)
    {
        $table = new Table();
        $connection = Requests::getConnection();

        mysqli_query($connection, self::getModifyColumnRequest($tableName, $params->call($table, $table))) or die(mysqli_error($connection));
    }

    public static function dropColumn($tableName, ... $columns)
    {
        foreach ($columns as $column) {
            $connection = Requests::getConnection();

            mysqli_query($connection, "ALTER TABLE `$tableName` DROP COLUMN `$column`") or die(mysqli_error($connection));
        }
    }

    public static function setDefault($tableName, $columnName, $value)
    {
        $connection = Requests::getConnection();
        mysqli_query($connection, "ALTER TABLE `$tableName` ALTER COLUMN `$columnName` SET DEFAULT '$value'") or die(mysqli_error($connection));
    }
}