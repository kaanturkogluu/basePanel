<?php

require_once __DIR__ . "/../core/config.php";
require_once __DIR__ . "/../core/db.php";


class BaseModel
{

    protected $table;
    protected $primaryKey = 'id';
    protected $tablePrefix;

    protected PDO $db;

    public function __construct(string $dbKey = 'default')
    {
        $this->db = Database::getInstance($dbKey)->getConnection();
    }

    public function get($columns = ['*'], $where = null, $orderBy = null, $limit = null)
    {
        if (is_array($columns)) {
            $columns = implode(', ', $columns);
        }

        $sql = "SELECT $columns FROM {$this->table}";
        $values = [];

        if ($where) {
            if (is_array($where)) {
                $conditions = [];
                foreach ($where as $key => $value) {
                    $conditions[] = "$key = :$key";
                    $values[$key] = $value;
                }
                $sql .= " WHERE " . implode(' AND ', $conditions);
            } else {
                $sql .= " WHERE $where";
            }
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }else{
            $sql .= " ORDER BY {$this->primaryKey} DESC";
   
        }

        if ($limit) {
            $sql .= " LIMIT $limit";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return $stmt->fetchAll();
    }

    public function findAll($conditions = [], $orderBy = null, $limit = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "$key = :$key";
                $params[":$key"] = $value;
            }
            $sql .= implode(' AND ', $whereClauses);
        }

        if ($orderBy) {
            $sql .= " ORDER BY $orderBy";
        }

        if ($limit) {
            $sql .= " LIMIT $limit";
        }


        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }



    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }


    public function create($data)
    {
        $fields = array_keys($data);
        $placeholders = array_map(function ($field) {
            return ":$field";
        }, $fields);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = array_keys($data);
        $setClauses = array_map(function ($field) {
            return "$field = :$field";
        }, $fields);

        $sql = "UPDATE {$this->table} 
                SET " . implode(', ', $setClauses) . " 
                WHERE {$this->primaryKey} = :id";

        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "$key = :$key";
                $params[$key] = $value;
            }
            $sql .= implode(' AND ', $whereClauses);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return isset($result['count']) ? (int) $result['count'] : 0;
    }




    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    public function commit()
    {
        return $this->db->commit();
    }

    public function rollBack()
    {
        return $this->db->rollBack();
    }
}