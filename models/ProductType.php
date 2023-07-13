<?php

class ProductType extends model
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = "products_types";
    }

    public function index()
    {
        $array = array();
        $dataReturn = array();
        $dataReturn['success'] = false;

        $sql = "SELECT * FROM " . $this->tableName . " WHERE active = true";
        $sql = $this->db->prepare($sql);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
            foreach ($array as $data) {
                $dataReturn['success'] = true;
                $dataReturn['data'] = [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'tax' => $data['tax'],
                    'created_at' => $data['created_at']
                ];
            }
        } else {
            $dataReturn['message'] = 'No types found';
        }

        return $dataReturn;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();
            $dataReturn['success'] = false;

            $sql = "INSERT INTO " . $this->tableName . " (name, tax) VALUES (:name, :tax)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':name', $params['name']);
            $sql->bindValue(':tax', $params['tax']);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $array = $sql->fetch();
                $dataReturn['success'] = true;
                $dataReturn['data'] = [
                    'id' => $this->db->lastInsertId(),
                    'name' => $params['name'],
                    'tax' => $params['tax'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
            } else {
                throw new \Exception('User not created');
            }
        } catch (\Exception $e) {
            // Check if message is SQLSTATE to return the message cleaner.
            $dataReturn = $this->checkSQLStateError($e, "Error registering type");
        } finally {
            return $dataReturn;
        }
    }

    public function read($params)
    {
        $array = array();
        $dataReturn = array();
        $dataReturn['success'] = false;

        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetch();
            $dataReturn['success'] = true;
            $dataReturn['data'] = [
                'id' => $array['id'],
                'name' => $array['name'],
                'tax' => $array['tax'],
                'active' => $array['active'],
                'created_at' => $array['created_at']
            ];
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'Type not found'
            ];
        }

        return $dataReturn;
    }

    public function modify($params)
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
