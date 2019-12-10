<?php


namespace base\model;

use base\interfaces\TableInterface;
use base\database\Database;

class Table implements TableInterface
{
    public $tableName;
    public $database;

    /**
     * Table constructor.
     */
    public function __construct()
    {
        $this->database = new Database();
    }


    /**
     * @param $object // Any class which extend Model
     * @return bool
     */
    public function insert($object): bool
    {
        $vars = get_object_vars($object);

        $flag = 0;
        $tableFields = "";
        foreach ($vars as $key => $var) {
            if ($key == 'status') {
                continue;
            }
            if ($flag) {
                $tableFields .= ", `{$key}`";
            }
            else {
                $tableFields .= "`{$key}`";
                $flag = 1;
            }
        }

        $flag = 0;
        $tableValues = "";
        foreach ($vars as $key => $var) {
            if ($key == 'status') {
                continue;
            }
            if ($flag) {
                $tableValues .= ", '{$var}'";
            }
            else {
                $tableValues .= "'{$var}'";
                $flag = 1;
            }
        }

        $sql = "
            INSERT INTO `{$this->tableName}` ({$tableFields}) VALUES ({$tableValues})
        ";



        return $this->database->query($sql);
    }

    /**
     * @param $whereCondition
     * @param $whereConditionValue
     * @param array $conditions - [condition => conditionValue]
     * @return bool
     */
    public function update($whereCondition, $whereConditionValue, $conditions): bool
    {
        $sql = "UPDATE `{$this->tableName}` SET ";
        $flag = 0;

        foreach ($conditions as $condition => $conditionValue) {
            if ($flag) {
                $sql .= ", `{$condition}` = '{$conditionValue}' ";
            }
            else {
                $sql .= "`{$condition}` = '{$conditionValue}' ";
                $flag = 1;
            }
        }

        $sql .= "WHERE `{$whereCondition}` = '{$whereConditionValue}'";

        return $this->database->query($sql);
    }

    /**
     * @return bool
     */
    public function deleteAll(): bool
    {
        // TODO: Implement deleteAll() method.
    }

    /**
     * @param string $condition
     * @param string|number $conditionValue
     * @return bool
     */
    public function deleteByCondition($condition, $conditionValue): bool
    {
        $sql = "DELETE FROM `{$this->tableName}` WHERE `{$condition}` = '{$conditionValue}'";

        return $this->database->query($sql);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM `{$this->tableName}`";

        return $this->database->getQueryArray($sql);
    }

    /**
     * @param string $condition
     * @param string|number $conditionValue
     * @return array
     */
    public function getByCondition($condition, $conditionValue): array
    {
        $sql = "SELECT * FROM `{$this->tableName}` WHERE `$condition` = '$conditionValue'";

        return $this->database->getQueryArray($sql);
    }
}