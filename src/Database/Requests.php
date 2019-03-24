<?php

class Requests
{
    protected static function getCreateTableRequest($tableName, $params)
    {
        $request = "CREATE TABLE `$tableName` (";
        $pk = array();

        $request .= self::foreachInRequest($params)['request'];
        $pk = self::foreachInRequest($params)['pk'];

        $count = 0;
        if ($pk) {
            $request .= ", PRIMARY KEY (";
            foreach ($pk as $key)
                if ($count)
                    $request .= ", `$key`";
                else
                    $request .= "`$key`";
            $request .= ")";
        }

        $request .= ") ENGINE = InnoDB";

        return $request;
    }

    protected static function getDropTableRequest($tableName)
    {
        return "DROP TABLE IF EXISTS `$tableName`";
    }

    protected static function getAddColumnRequest($tableName, $params)
    {
        $request = "ALTER TABLE `$tableName` ";
        $request .= self::foreachInRequest($params, "add")['request'];

        return $request;
    }

    protected static function getModifyColumnRequest($tableName, $params)
    {
        $request = "ALTER TABLE `$tableName` ";
        $request .= self::foreachInRequest($params, "modify column")['request'];

        return $request;
    }

    protected static function getDropColumnRequest($tableName, $columnName)
    {
        return "ALTER TABLE `$tableName` DROP COLUMN `$columnName`";
    }

    protected static function getDefaultValueRequest($tableName, $columnName, $value)
    {
        return "ALTER TABLE `$tableName` ALTER COLUMN `$columnName` SET DEFAULT '$value'";
    }

    private static function foreachInRequest($info, $param = "")
    {
        $request = '';
        $pk = array();
        $count = 0;

        foreach ($info as $columnName => $column) {
            if ($count)
                $request .= ", $param `$columnName`";
            else
                $request .= "$param `$columnName`";

            $type = 1;
            foreach ($column as $key => $value) {
                if ($type)
                    if ($value)
                        $request .= " $key($value)";
                    else
                        $request .= " $key";


                if ($key == 'primary key' && $value != null) {
                    $pk[] = $columnName;
                    continue;
                }

                if ($value && !$type)
                    $request .= " $key";

                $type = 0;
            }

            $count = 1;
        }

        return [
            'request' => $request,
            'pk' => $pk
        ];
    }
}