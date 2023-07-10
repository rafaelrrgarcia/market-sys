<?php

class Product extends model
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = "products";
    }

    public function index()
    {
        $array = array();
        $dataReturn = array();

        $sql = "SELECT * FROM " . $this->tableName . " WHERE active = true";
        $sql = $this->db->prepare($sql);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
            foreach ($array as $data) {
                $dataReturn[] = [
                    'id' => $data['id'],
                    'id_type' => $data['id_type'],
                    'name' => $data['name'],
                    'value' => $data['value'],
                    'created_at' => $data['created_at']
                ];
            }
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'No products found'
            ];
        }

        return $dataReturn;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();

            $sql = "INSERT INTO " . $this->tableName . " (id_type, name, value) VALUES (:id_type, :name, :value)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_type', $params['id_type']);
            $sql->bindValue(':name', $params['name']);
            $sql->bindValue(':value', $params['value']);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $array = $sql->fetch();
                $dataReturn = [
                    'id' => $this->db->lastInsertId(),
                    'id_type' => $params['id_type'],
                    'name' => $params['name'],
                    'value' => $params['value'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
            } else {
                throw new \Exception('Product not created');
            }
        } catch (\Exception $e) {
            // Check if message is SQLSTATE to return the message cleaner.
            $dataReturn = $this->checkSQLStateError($e, "Error creating the new product");
        } finally {
            return $dataReturn;
        }
    }

    public function read($params)
    {
        $array = array();
        $dataReturn = array();

        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetch();
            $dataReturn = [
                'id' => $array['id'],
                'id_type' => $array['id_type'],
                'name' => $array['name'],
                'value' => $array['value'],
                'active' => $array['active'],
                'created_at' => $array['created_at']
            ];
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'Product not found'
            ];
        }

        return $dataReturn;
    }

    public function update($params)
    {
        $dataReturn = array();

        $sql = "UPDATE " . $this->tableName . " SET name = :name, tax = :tax WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':name', $params['name']);
        $sql->bindValue(':tax', $params['tax']);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dataReturn = ['success' => true, 'message' => 'Product Type successfully updated'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Product Type not updated'];
        }

        return $dataReturn;
    }

    public function delete($params)
    {
        $dataReturn = array();

        $sql = "UPDATE " . $this->tableName . " SET active = false WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dataReturn = ['success' => true, 'message' => 'Product Type deleted'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Product Type not deleted'];
        }

        return $dataReturn;
    }
}
