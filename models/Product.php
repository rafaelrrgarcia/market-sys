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
        $return = array();

        $sql = "SELECT 
                    p.id,
                    p.name as productname,
                    p.value as productvalue,
                    t.name as type,
                    t.tax,
                    (p.value * t.tax) as taxvalue,
                    ((p.value * t.tax) + p.value) as totalvalue
                FROM " . $this->tableName . " p
                JOIN products_types t ON p.id_type = t.id
                WHERE p.active = true ORDER BY p.created_at DESC";
        $sql = $this->db->prepare($sql);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $return['success'] = true;
            $array = $sql->fetchAll();
            foreach ($array as $data) {
                $return['data'][] = $this->setReturnFields([
                    'id',
                    'productname',
                    'productvalue',
                    'type',
                    'tax',
                    'taxvalue',
                    'totalvalue',
                ], $data);
            }
        } else {
            $return = [
                'success' => false,
                'message' => 'No users found'
            ];
        }

        return $return;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();
            $dataReturn['success'] = false;

            $sql = "INSERT INTO " . $this->tableName . " (id_type, name, value) VALUES (:id_type, :name, :value)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_type', $params['id_type']);
            $sql->bindValue(':name', $params['name']);
            $sql->bindValue(':value', $params['value']);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $array = $sql->fetch();
                $dataReturn['success'] = true;
                $dataReturn['data'] = [
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
            $dataReturn['message'] = $this->checkSQLStateError($e, "Error creating the new product");
        } finally {
            return $dataReturn;
        }
    }

    public function read($params)
    {
        $array = array();
        $dataReturn = array();
        $dataReturn['success'] = false;

        $sql = "SELECT 
                    p.id,
                    p.name as productname,
                    p.value as productvalue,
                    t.name as type,
                    t.tax,
                    (p.value * t.tax) as taxvalue,
                    ((p.value * t.tax) + p.value) as totalvalue
                FROM " . $this->tableName . " p
                JOIN products_types t ON p.id_type = t.id
                WHERE p.active = true AND p.id = :id ORDER BY p.created_at DESC";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetch();
            $dataReturn['success'] = true;
            $dataReturn['data'] = $this->setReturnFields([
                'id',
                'productname',
                'productvalue',
                'type',
                'tax',
                'taxvalue',
                'totalvalue',
            ], $array);
        } else {
            $dataReturn['message'] = 'Product not found';
        }

        return $dataReturn;
    }

    public function modify($params)
    {
        $dataReturn = array();

        $sql = "UPDATE " . $this->tableName . " SET name = :name, value = :value, id_type = :id_type WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':name', $params['name']);
        $sql->bindValue(':value', $params['value']);
        $sql->bindValue(':id_type', $params['id_type']);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dataReturn = ['success' => true, 'message' => 'Product successfully updated'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Product not updated'];
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
