<?php

class Billing extends model
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = "billings";
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
                    'id_user' => $data['id_user'],
                    'id_product' => $data['id_product'],
                    'id_cart' => $data['id_cart'],
                    'amount' => $data['amount'],
                    'created_at' => $data['created_at']
                ];
            }
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'No billings found'
            ];
        }

        return $dataReturn;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();

            $sql = "INSERT INTO " . $this->tableName . " (id_user, id_product, id_cart, amount) VALUES (:id_user, :id_product, :id_cart, :amount)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_user', $params['id_user']);
            $sql->bindValue(':id_product', $params['id_product']);
            $sql->bindValue(':id_cart', $params['id_cart']);
            $sql->bindValue(':amount', $params['amount']);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $array = $sql->fetch();
                $dataReturn = [
                    'id' => $this->db->lastInsertId(),
                    'id_user' => $params['id_user'],
                    'id_product' => $params['id_product'],
                    'id_cart' => $params['id_cart'],
                    'amount' => $params['amount'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
            } else {
                throw new \Exception('Billing not created');
            }
        } catch (\Exception $e) {
            // Check if message is SQLSTATE to return the message cleaner.
            $dataReturn = $this->checkSQLStateError($e, "Error creating the new billing");
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
                'id_user' => $array['id_user'],
                'id_product' => $array['id_product'],
                'id_cart' => $array['id_cart'],
                'amount' => $array['amount'],
                'active' => $array['active'],
                'created_at' => $array['created_at']
            ];
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'Billing not found'
            ];
        }

        return $dataReturn;
    }

    public function update($params)
    {
        $dataReturn = array();

        $sql = "UPDATE " . $this->tableName . " SET name = :amount WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':amount', $params['amount']);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dataReturn = ['success' => true, 'message' => 'Billing successfully updated'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Billing not updated'];
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
            $dataReturn = ['success' => true, 'message' => 'Billing deleted'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Billing not deleted'];
        }

        return $dataReturn;
    }
}
