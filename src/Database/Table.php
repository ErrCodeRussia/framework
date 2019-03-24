<?php

class Table
{
    private $columns;
    private $last;

    public function increments(string $columnName, int $size)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('int', $size);

        $this->columns[$columnName]['primary key'] = true;
        $this->columns[$columnName]['not null'] = true;
        $this->columns[$columnName]['auto_increment'] = true;
    }

    public function tinyint(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('tinyint');
        return $this;
    }

    public function smallint(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('smallint');
        return $this;
    }

    public function mediumint(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('mediumint');
        return $this;
    }

    public function int(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('int');
        return $this;
    }

    public function bigint(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('bigint');
        return $this;
    }

    public function float(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('float');
        return $this;
    }

    public function double(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('double');
        return $this;
    }

    public function boolean(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('boolean');
        return $this;
    }

    public function date(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('date');
        return $this;
    }

    public function time(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('time');
        return $this;
    }

    public function year(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('year');
        return $this;
    }

    public function datetime(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('datetime');
        return $this;
    }

    public function char(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('char');
        return $this;
    }

    public function varchar(string $columnName, int $size)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('varchar', $size);
        return $this;
    }

    public function tinytext(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('tinytext');
        return $this;
    }

    public function text(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('text');
        return $this;
    }

    public function mediumtext(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('mediumtext');
        return $this;
    }

    public function longtext(string $columnName)
    {
        $this->last = $columnName;
        $this->columns[$columnName] = $this->setColumnInfo('longtext');
        return $this;
    }

    public function primary_key()
    {
        if ($this->last) {
            $this->columns[$this->last]['primary key'] = true;
            return $this;
        }
    }

    public function not_null()
    {
        if ($this->last) {
            $this->columns[$this->last]['not null'] = true;
            return $this;
        }
    }

    public function unique()
    {
        if ($this->last) {
            $this->columns[$this->last]['unique'] = true;
            return $this;
        }
    }

    public function binary()
    {
        if ($this->last) {
            $this->columns[$this->last]['binary'] = true;
            return $this;
        }
    }

    public function unsigned()
    {
        if ($this->last) {
            $this->columns[$this->last]['unsigned'] = true;
            return $this;
        }
    }

    public function zerofill()
    {
        if ($this->last) {
            $this->columns[$this->last]['zerofill'] = true;
            return $this;
        }
    }

    public function auto_increment()
    {
        if ($this->last) {
            $this->columns[$this->last]['auto_increment'] = true;
            return $this;
        }
    }

    public function generated()
    {
        if ($this->last) {
            $this->columns[$this->last]['generated'] = true;
            return $this;
        }
    }

    public function default_value($value)
    {
        if ($this->last) {
            $this->columns[$this->last]['default'] = $value;
            return $this;
        }
    }



    /**
     * @return mixed
     */
    public function getColumns()
    {
        return $this->columns;
    }

    private function setColumnInfo($type, $size = null)
    {
        return [
            $type => $size,
            'primary key' => false,
            'not null' => false,
            'unique' => false,
            'binary' => false,
            'unsigned' => false,
            'zerofill' => false,
            'auto_increment'=> false,
            'generated' => false,
            'default' => null
        ];
    }


}