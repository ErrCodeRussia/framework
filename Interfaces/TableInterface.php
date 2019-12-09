<?php


namespace base\interfaces;


interface TableInterface
{
    /**
     * @param $object // Any class which implements Model
     * @return bool
     */
    public function insert($object) : bool;

    /**
     * @param $whereCondition
     * @param $whereConditionValue
     * @param array $conditions - [condition => conditionValue]
     * @return bool
     */
    public function update($whereCondition, $whereConditionValue, $conditions): bool;

    /**
     * @return bool
     */
    public function deleteAll() : bool;

    /**
     * @param string $condition
     * @param string|number $conditionValue
     * @return bool
     */
    public function deleteByCondition($condition, $conditionValue) : bool;

    /**
     * @return array
     */
    public function getAll() : array;

    /**
     * @param string $condition
     * @param string|number $conditionValue
     * @return array
     */
    public function getByCondition($condition, $conditionValue) : array ;
}