<?php


namespace base\database;

use base\exceptions\database\OffsetException;
use base\exceptions\database\SelectArrayException;
use base\exceptions\database\SelectException;
use base\exceptions\database\WrongValueException;
use base\interfaces\TableInterface;
use PDOStatement;

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
     *  Вставка нового значения в таблицу
     *
     *  @param $object
     *  Для формирования запроса используются свойства объекта класса, дочернего
     * к Model. В нём свойства соответствуют названиям столбцов в таблице. В функцию
     * передаётся объект с заполненными свойствами (то есть на каждое свойство есть
     * своё значение).
     *
     * @return int
     */
    public function insert($object): int
    {
        $vars = get_object_vars($object);
        $ai = $object->getAutoIncrement();

        $flag = 0;
        $tableFields = "";
        foreach ($vars as $key => $var) {
            if ($this->checkAI($ai, $key)) {
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
            if ($this->checkAI($ai, $key)) {
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

        return $this->database->exec($sql);
    }

    /**
     *  Обновление данных в таблице по нескольким условиям
     *
     *  @param array $params
     *  Столбцы и новые значения, которые нужно установить. Сюда передаётся
     * массив вида ['column' => 'value']
     *
     *
     *  @param array|string $where
     *  @return int
     */
    public function update($params, $where): int
    {
        $columns = '';
        foreach ($params as $column => $value) {
            if ($columns == '') {
                $columns .= "{$column} = '{$value}' ";
            }
            else {
                $columns .= ", {$column} = '{$value}' ";
            }
        }

        $sql = "UPDATE {$this->tableName} SET {$columns} {$this->getWhere($where)}";

        return $this->database->exec($sql);
    }

    /**
     *
     * @param $where
     * @return int
     */
    public function delete($where): int
    {
        $sql = "DELETE FROM {$this->tableName} ";

        $sql .= $this->getWhere($where);

        return $this->exec($sql);
    }

    /**
     *  Получение выборки из базы данных
     *
     *  @param array|string $select
     *  Первым аргументом передаётся массив столбцов, которые необходимо вернуть.
     * Если требуются все столбцы, вместо массива передайте строку '*'.
     *
     *
     *  @param array|null $where
     *  Если нужно ограничить выборку каким-либо условием, используйте этот параметр.
     * Передать нужно массив вида ['column_name' => 'value', 'column_name' => 'value', ...].
     * По умолчанию несколько условий связываются оператором AND, то есть результат
     * будет получен только когда все условия будут выполнены.
     *
     *  Если нужно использовать оператор OR или сложную конструкцию, опишите отдельный
     * метод в классе вашей таблицы и обратитесь к методу fetchAll.
     *
     *
     *  @param array|null $order_by
     *  Для сортировки выборки передайте сюда массив вида ['column1', 'column2', ...].
     * Также здесь можно указать сортировку по убыванию. Для этого передайте такую запись:
     * [..., 'columnN desc', ...].
     *
     *
     *  @param number|null $limit
     *  @param number|null $offset
     *  LIMIT - количество возвращаемых записей. Например, если нужно получить
     * первые 10 записей.
     *  OFFSET - смещение относительно начала выборки. Для его использования
     * ОБЯЗАТЕЛЬНО нужно передать LIMIT.
     *
     *  Например, если передать LIMIT = 10 и OFFSET = 5, на выходе будут записи
     * с 6 по 15.
     *
     *
     *  @return array
     *  Возвращается массив массивов вида [0 => [], 1 => [], 2 => []].
     */
    public function get($select, $where = null, $order_by = null, $limit = null, $offset = null): array
    {
        try {
            if ($select == '*') {
                $columns = $select;
            } elseif (is_array($select)) {
                if (!count($select)) {
                    throw new SelectArrayException();
                }

                $columns = '';
                foreach ($select as $item) {
                    if ($columns == '')
                        $columns .= "{$item}";
                    else
                        $columns .= ", {$item}";
                }
            } else {
                throw new SelectException();
            }
        }
        catch (SelectArrayException $e) {
            echo $e->getMessage();
            die();
        }
        catch (SelectException $e) {
            echo $e->getMessage();
            die();
        }

        $whe = '';
        if (isset($where) && $this->checkWrongValue('array', $where)) {
            $whe .= "WHERE ";
            foreach ($where as $key => $value) {
                if ($whe == "WHERE ")
                    $whe .= "{$key} = '{$value}'";
                else
                    $whe .= " AND {$key} = '{$value}'";
            }
        }

        $sort = '';
        if (isset($order_by) && $this->checkWrongValue('array', $order_by)) {
            $sort = 'ORDER BY ';
            foreach ($order_by as $item) {
                if ($sort == 'ORDER BY ')
                    $sort .= $item;
                else
                    $sort .= ', ' . $item;
            }
        }

        $lim = '';
        if (isset($limit) && $this->checkWrongValue('number', $limit)) {
            $lim = "LIMIT {$limit}";
        }

        $off = '';
        if (isset($offset) && $this->checkWrongValue('number', $offset)) {
            try {
                if ($limit === null)
                    throw new OffsetException();
            }
            catch (OffsetException $e) {
                echo $e->getMessage();
                die();
            }

            $off = "OFFSET {$offset}";
        }

        $sql = "SELECT {$columns} FROM {$this->tableName} {$whe} {$sort} {$lim} {$off}";

        return $this->database->getQueryArray($sql);
    }

    /**
     *  Запрос к базе данных, который возвращает количество затронутых строк
     * или true/false
     *
     *  Эту функцию нужно использовать в случаях, когда запрос не возвращает
     * выборку из базы данных. Например, при вставке или удалении записей.
     * INSERT-запрос возвращает true/false, а DELETE-запрос возвращает int/false
     *
     * @param $sql      - строка SQL-запроса
     * @return int|bool - количество затронутых строк или true/false
     */
    public function exec($sql) : int
    {
        return $this->database->exec($sql);
    }

    /**
     *  Запрос к базе данных
     *
     *  Эта функция - аналог функции get, где возвращается выборка из базы
     * данных. Возвращается объект PDOStatement
     *
     * @param $sql - SQL-запрос
     * @return PDOStatement
     */
    public function query($sql) : PDOStatement
    {
        return $this->database->query($sql);
    }

    private function checkAI($ai, $param) : bool
    {
        foreach ($ai as $item) {
            if ($item == $param)
                return true;
        }

        return false;
    }

    /**
     *  Проверка на допустимость значения
     *
     *  @param $type     - требуемый тип
     *  Допустимые значения:
     * - array
     * - number
     * - int
     * - float
     * - bool
     * - string
     *
     *  @param $value    - элемент, у которого нужно проверить тип
     *  @return bool
     */
    private function checkWrongValue($type, $value)
    {
        try {
            switch ($type) {
                case "array":
                    if (!is_array($value)) {
                        throw new WrongValueException($value);
                    }
                    break;
                case "number":
                    if (!is_numeric($value)) {
                        throw new WrongValueException($value);
                    }
                    break;
                case "int":
                    if (!is_int($value)) {
                        throw new WrongValueException($value);
                    }
                    break;
                case "float":
                    if (!is_float($value)) {
                        throw new WrongValueException($value);
                    }
                    break;
                case "bool":
                    if (!is_bool($value)) {
                        throw new WrongValueException($value);
                    }
                    break;
                case "string":
                    if (!is_string($value)) {
                        throw new WrongValueException($value);
                    }
                    break;
                default:
                    return false;
            }

            return true;
        }
        catch (WrongValueException $e) {
            echo $e->getMessage();
            die();
        }
    }

    /**
     *  Получение условия WHERE для SQL-запроса
     *
     *  @param $where    - массив вида ['column' => 'value']
     *  @return string   - либо '', если WHERE = '*', либо готовая часть
     * SQL-запроса
     */
    private function getWhere($where)
    {
        $whe = '';
        if ($where == '*') {
            $whe .= '';
        }
        elseif (is_array($where)) {
            try {
                if (!count($where)) {
                    throw new SelectArrayException();
                }
            } catch (SelectArrayException $e) {
                echo $e->getMessage();
                die();
            }

            $whe .= 'WHERE ';
            foreach ($where as $key => $item) {
                if ($whe == 'WHERE ')
                    $whe .= "{$key} = '{$item}'";
                else
                    $whe .= " AND {$key} = '{$item}'";
            }
        }
        else {
            try {
                throw new SelectException();
            }
            catch (SelectException $e) {
                echo $e->getMessage();
                die();
            }
        }

        return $whe;
    }
}