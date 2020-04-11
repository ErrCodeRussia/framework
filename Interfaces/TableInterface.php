<?php


namespace base\interfaces;


interface TableInterface
{
    /**
     * @param $object // Any class which implements Model
     * @return int|array
     */
    public function insert($object);

    /**
     * @param $params
     * @param $where
     * @return int|array
     */
    public function update($params, $where);

    /**
     * @param $where
     * @return int|array
     */
    public function delete($where);

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