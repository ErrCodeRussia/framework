<?php


namespace base\interfaces;


interface TableInterface
{
    /**
     * @param $object // Any class which implements Model
     * @return int
     */
    public function insert($object) : int;

    /**
     * @param $params
     * @param $where
     * @return int
     */
    public function update($params, $where): int;

    /**
     * @param $where
     * @return int
     */
    public function delete($where) : int;

    /**
     * @param array|string $select
     * @param array|null $where
     * @param array|null $order_by
     * @param number|null $limit
     * @param number|null $offset
     * @return array
     */
    public function get($select, $where = null, $order_by = null, $limit = null, $offset = null) : array;
}