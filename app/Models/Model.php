<?php

namespace App\Models;

use App\Exceptions\ModelNotFoundException;
use App\Libs\DBConnection;
use PDO;

abstract class Model
{
    private $db;

    protected $table;
    protected $attributes;

    private $where;
    private $joins;

    function __construct($attributes = [])
    {
        $this->db = null;
        $this->fill($attributes);
    }

    public function fill($attributes = [])
    {
        $this->attributes = $attributes;
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }
    }

    private function getDBConnection(): DBConnection
    {
        if (empty($this->db)) {
            $this->db = new DBConnection;
            $this->db->setClassToFetch(get_class($this));
        }

        return $this->db;
    }

    public function get($columns = ['*'])
    {
        $columns = implode(",", $columns);
        $sql = "SELECT {$columns} FROM {$this->getTable()}";

        if (!empty($this->joins)) {
            $sql .= " " . $this->joins;
        }

        if (!empty($this->where)) {
            $sql = $this->addWhereToSql($sql, $this->where);
        }

        return $this->getDBConnection()->selectQuery($sql, PDO::FETCH_CLASS);
    }

    public function first($columns = ['*'])
    {
        $columns = implode(",", $columns);
        $sql = "SELECT {$columns} FROM {$this->getTable()}";

        if (!empty($this->where)) {
            $sql = $this->addWhereToSql($sql, $this->where);
        }

        return $this->getDBConnection()->selectOneQuery($sql, PDO::FETCH_CLASS);
    }

    public function firstOrFail($columns = ['*'])
    {
        if ($result = $this->first($columns)) return $result;
        throw new ModelNotFoundException;
    }

    public function find($primary): self
    {
        $sql = "SELECT * FROM {$this->getTable()} WHERE `id` = {$primary}";
        return $this->getDBConnection()->selectOneQuery($sql, PDO::FETCH_CLASS);
    }

    public function findOrFail($primary)
    {
        if ($result = $this->find($primary)) return $result;
        throw new ModelNotFoundException;
    }

    public function save()
    {
        $keys = array_keys($this->attributes);

        if ($this->id) {
            $this->update($keys);
        } else {
            $this->create($keys);
        }

        return true;
    }

    private function create($keys)
    {
        $columns = implode(",", $keys);
        $keysToBindParam = implode(", ", array_map(fn ($item) => ":{$item}", $keys));

        $sql = "INSERT INTO {$this->getTable()} ({$columns}) VALUES ({$keysToBindParam})";
        $this->getDBConnection()->insertQuery($sql, $this->attributes);

        $this->id = $this->getDBConnection()->lastInsertId();
    }

    private function update(array $keys)
    {
        $sql = "UPDATE {$this->getTable()} SET ";

        foreach ($keys as $key => $value) {
            $sql .= "`{$value}`=:{$value}";
            if ($key >= (count($keys) - 1)) break;
            $sql .= ",";
        }

        $this->getDBConnection()->insertQuery($sql, $this->attributes);
    }

    private function addWhereToSql(string $sql, array $where): string
    {
        $sql .= ' WHERE ';

        if (is_array($where[0])) {
            foreach ($where as $key => $value) {
                $sql .= $this->formatWhere($value);
                if ($key >= (count($where) - 1)) break;
                $sql .= ' AND ';
            }
        } else {
            $sql .= $this->formatWhere($where);
        }

        return $sql;
    }

    private function formatWhere(array $where)
    {
        $sqlWhere = "`{$where[0]}` {$where[1]} ";
        $sqlWhere .= ($where[2] === NULL) ? "NULL" :  "'{$where[2]}'";
        return $sqlWhere;
    }

    protected function getTable(): string
    {
        return $this->table;
    }

    public function setWhere(array $where)
    {
        $this->where = $where;
    }

    public function setJoin(string $joins)
    {
        $this->joins = $joins;
    }
}
