<?php

namespace App\Core;

use Exception;
use PDO;
use PDOStatement;
use RuntimeException;

class QueryBuilder
{
    private $sql;
    private $value;
    private $columns;
    private $result;
    private $table;
    private $tableProp;
    private $sample;
    private $join;
    private $properties;
    private $relations;
    private $where;
    private $andWhere;
    private $orWhere;
    private PDO $db;
    private int $limit;

    public function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function fetch(string $table, $sample = ''): self
    {
        if (!$this->isEmpty($sample)) {
            $this->setSample($sample);
            $this->setValue($this->getValue($sample));
        }
        $this->setTable($table);
        $this->setColumns(" $table.ID, $table.NAME");

        return $this;
    }

    public function leftJoin(string $tableProp, array $props, array $relations): self
    {
        $this->setTableProp($tableProp);
        $this->setProperties($this->getProps($props));
        $relation1 = $this->getRelation($relations);
        $relation2 = $this->getRelation($this->relations);
        $this->setJoin(" LEFT JOIN $this->tableProp ON $relation1 = $relation2");
        return $this;
    }

    public function select(array $select): self
    {
        $this->setColumns($this->getColumns($select));

        return $this;
    }

    public function where(string $exp1, string $cond, string $exp2): self
    {
        $this->setWhere($this->getWhere($exp1, $cond, $exp2));

        return $this;
    }

    public function andWhere(string $exp1, string $cond, string $exp2): self
    {
        $this->setAndWhere($this->getWhere($exp1, $cond, $exp2));

        return $this;
    }

    public function orWhere(string $exp1, string $cond, string $exp2): self
    {
        $this->setOrWhere($this->getWhere($exp1, $cond, $exp2));

        return $this;
    }

    public function get(): array
    {
        $this->setSql("SELECT $this->columns"."$this->properties FROM $this->table"."$this->join");

        if (!$this->isEmpty($this->value)) {
            $this->setSql(" WHERE $this->table.$this->value = '$this->sample'");
        } elseif (!$this->isEmpty($this->where)) {
            $this->setSql(" WHERE $this->where $this->andWhere $this->orWhere");
        }

//        if ($this->limit > 0) {
//            $this->setSql(" LIMIT $this->limit");
//        }

        $stmt = $this->query($this->sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->result = array_change_key_case($result, CASE_LOWER);

        return $this->result;
    }

    private function getWhere(string $exp1, string $cond, string $exp2): string
    {
        try {
            $this->checkWhere($exp1, $cond, $exp2);
        } catch (Exception $e) {
            die($e);
        }

        return ' '.$exp1.' '.$cond.' '."'$exp2'";
    }

    private function checkWhere(string $column, string $cond, string $value): void
    {
        if (empty($column) || empty($cond) || empty($value)) {
            if ((!is_numeric($value)) || empty($column)) {
                throw new RuntimeException('Incomplete - WHERE');
            }
        }
        if ($this->isCondition($cond)) {
            return;
        }

        throw new RuntimeException('The wrong sign in WHERE');
    }

    private function isCondition(string $code): bool
    {
        $array = ['=', '>', '<', '<=', '>='];

        return in_array($code, $array);
    }

    private function getRelation(array $relations): string
    {
        $relation = $relations;
        $keys = array_keys($relation);
        $values = array_values($relation);
        $res = array_shift($keys).'.'.array_shift($values);
        $relation = array_combine($keys, $values);
        $this->relations = $relation;

        return $res;
    }

    private function getProps(array $props): string
    {
        return ", $this->tableProp." . implode(", $this->tableProp.", array_values($props));
    }

    private function getValue($sample): string
    {
        try {
            $value = $this->checkValue($sample);
        } catch (Exception $e) {
            die($e);
        }

        return $value;
    }

    private function checkValue($sample): string
    {
        if (!is_string($sample) && !is_int($sample)) {
            throw new RuntimeException('Must be of the type string or integer, '.gettype($sample).' given');
        }
        $value = 'ID';
        if (!is_numeric($sample)) {
            $value = 'CODE';
        }

        return $value;
    }

    public function insert(array $values): array
    {
        $keys = array_keys($values);

        $keys = implode(', ', $keys);

        $this->setSql("INSERT INTO $this->table ($keys) VALUES (:" . implode(', :', array_keys($values)) . ")");
        $this->query($this->sql, $values);

        return $this->fetch($this->table)->select(['*'])->where('id', '=', $this->db->lastInsertId())->get()[0];
    }

    public function insertOnUpdate(array $values): void
    {
        $keys = array_keys($values);

        $keys = implode(', ', $keys);

        $this->setSql("INSERT INTO $this->table ($keys) VALUES (:" . implode(', :', array_keys($values)) . ")");
        $res = implode(', ', array_map(
            function ($key) { return $key . ' = :' . $key; },
            array_keys($values)
        ));

        $this->setSql(" ON DUPLICATE KEY UPDATE $res");
        $this->query($this->sql, $values);
    }

    public function update(array $where, array $values): array
    {
        $keys = $values['id'] ?? [];

        $sql = [];

        foreach ($values as $key => $value) {
            if ($key === 'id' || $value === '') {
                continue;
            }

            $sql[] = "$key = :$key";
            $keys[$key] = $value;
        }

        $sql = implode(', ', $sql);
        $this->setSql("UPDATE $this->table SET $sql WHERE " . $where[0] . "=:" . $where[0] . " AND last_insert_id(id)");
        $this->query($this->sql, array_merge($keys, [$where[0] => $where[1]]));

        return $this->fetch($this->table)->select(['*'])->where('id', '=', $this->db->lastInsertId())->get()[0];
    }

    public function delete(string $field, string $value): void
    {
        $this->setSql("DELETE FROM $this->table WHERE " . $field . " = :value");
        $this->query($this->sql, ['value' => $value]);
    }

    private function isEmpty($value): bool
    {
        return empty($value);
    }

    private function getColumns(array $select): string
    {
        return "$this->table." . implode(", $this->table.", array_values($select));
    }

    private function setSql(string $sql): void
    {
        $this->sql .= $sql;
    }

    private function setTable($table): void
    {
        $this->table = $table;
    }

    private function setValue($value): void
    {
        $this->value = $value;
    }

    private function setSample($sample): void
    {
        $this->sample = $sample;
    }

    private function setColumns($columns): void
    {
        $this->columns = $columns;
    }

    private function setJoin($join): void
    {
        $this->join .= $join;
    }

    private function setProperties($properties): void
    {
        $this->properties .= $properties;
    }

    private function setTableProp($tableProp): void
    {
        $this->tableProp = $tableProp;
    }

    private function setWhere($where): void
    {
        $this->where = $where;
    }

    private function setAndWhere($andWhere): void
    {
        $this->andWhere .= ' AND '.$andWhere;
    }

    private function setOrWhere($orWhere): void
    {
        $this->orWhere .= ' OR '.$orWhere;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function query($sql, $params = []): bool|PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            //Биндим
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }

        $stmt->execute();
        $this->sql = '';

        return $stmt;
    }
}
