<?php

namespace base\database\schema;

class SchemaRequests
{
    /**
     *  Получение запроса на создание таблицы
     *
     * @param string $tableName - название таблиы
     * @param array $params     - массив параметров
     *
     * @return string           - запрос
     */
    public static function getCreateTableRequest(string $tableName, array $params)
    {
        $request = "CREATE TABLE `$tableName` (";
        $pk = array();

        $foreach = self::foreachInRequest($params);
        $request .= $foreach['request'];
        $pk = $foreach['pk'];

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

    /**
     *  Получение запроса на удаление таблицы
     *
     * @param string $tableName - название таблицы
     *
     * @return string           - запрос
     */
    public static function getDropTableRequest(string $tableName)
    {
        return "DROP TABLE IF EXISTS `$tableName`";
    }

    /**
     *  Получение запроса на создание колонки
     *
     * @param string $tableName - название таблицы
     * @param array $params     - массив параметров
     *
     * @return string           - запрос
     */
    public static function getAddColumnRequest(string $tableName, array $params)
    {
        $request = "ALTER TABLE `$tableName` ";
        $request .= self::foreachInRequest($params, "add")['request'];

        return $request;
    }

    /**
     *  Получение запроса на изменение колонки
     *
     * @param string $tableName - название таблицы
     * @param array $params     - массив параметров
     *
     * @return string           - запрос
     */
    public static function getModifyColumnRequest(string $tableName, array $params)
    {
        $request = "ALTER TABLE `$tableName` ";
        $request .= self::foreachInRequest($params, "modify column")['request'];

        return $request;
    }

    /**
     *  Получение запроса на удаление колонки
     *
     * @param string $tableName     - название таблицы
     * @param string $columnName    - название колонки
     *
     * @return string               - запрос
     */
    public static function getDropColumnRequest(string $tableName, string $columnName)
    {
        return "ALTER TABLE `$tableName` DROP COLUMN `$columnName`";
    }

    /**
     *  Получение запроса на установку значения по умолчанию
     *
     * @param string $tableName     - название таблицы
     * @param string $columnName    - название колонки, для которой нужно поставить значение по умолчанию
     * @param $value                - значеие по умолчанию
     *
     * @return string               - запрос
     */
    public static function getDefaultValueRequest(string $tableName, string $columnName, $value)
    {
        return "ALTER TABLE `$tableName` ALTER COLUMN `$columnName` SET DEFAULT '$value'";
    }

    /**
     *  Проход по массиву с параметрами (данные о колонках)
     *
     * @param array $info   - массив входящих параметров
     * @param string $param - параметр операции (например, ADD)
     *
     * @return array ['request', 'pk']
     */
    private static function foreachInRequest(array $info, string $param = "")
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