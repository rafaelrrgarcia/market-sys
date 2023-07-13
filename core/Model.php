<?php

class Model
{
    protected $db;
    protected $tableName;
    protected $joins = [];

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    // ORM
    protected function select($fields = [], $where = [], $order = [], $limit = [])
    {
        $sql = "SELECT ";
        if (count($fields) > 0) {
            $sql .= implode(", ", $fields);
        } else {
            $sql .= "*";
        }
        $sql .= " FROM " . $this->tableName;

        // joins
        if (isset($this->joins)) {
            foreach ($this->joins as $join) {
                $sql .= " " . $join;
            }
        }

        if (count($where) > 0) {
            $sql .= " WHERE ";
            $sql .= implode(" AND ", $where);
        }
        if (count($order) > 0) {
            $sql .= " ORDER BY ";
            $sql .= implode(", ", $order);
        }
        if (count($limit) > 0) {
            $sql .= " LIMIT ";
            $sql .= implode(", ", $limit);
        }
        $sql .= ";";
        //die($sql);
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return [
                'success' => true,
                'data' => $result
            ];
        } catch (PDOException $e) {
            return $this->checkSQLStateError($e, $sql);
        }
    }

    protected function insert($data)
    {
        $sql = "INSERT INTO " . $this->tableName . " (";
        $sql .= implode(", ", array_keys($data));
        $sql .= ") VALUES (";
        $sql .= ":" . implode(", :", array_keys($data));
        $sql .= ");";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            return [
                'success' => true,
                'data' => $this->setReturnFields(array_keys($data), $data)
            ];
        } catch (PDOException $e) {
            return $this->checkSQLStateError($e, $sql);
        }
    }

    protected function update($data, $where)
    {
        $sql = "UPDATE " . $this->tableName . " SET ";
        $sql .= implode(" = ?, ", array_keys($data));
        $sql .= " = ? WHERE ";
        $sql .= implode(" AND ", array_keys($where));
        $sql .= " = ?;";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_merge(array_values($data), array_values($where)));
            return [
                'success' => true,
                'data' => $this->setReturnFields(array_keys($data), $data)
            ];
        } catch (PDOException $e) {
            return $this->checkSQLStateError($e, $sql);
        }
    }

    protected function checkSQLStateError($e, $addText = ""){
        if (strpos($e->getMessage(), 'SQLSTATE') !== false) { 
            return [
               'success' => false,
               'message' => trim('SQL intern error. ' . $addText)
           ];
       } else {
            return [
               'success' => false,
               'message' => $e->getMessage()
           ];
       }
    }

    protected function setReturnFields($fields, $array){
        $dataReturn = array();
        foreach ($fields as $field) {
            $dataReturn[$field] = $array[$field];
        }
        return $dataReturn;
    }
}
