<?php

class Cart extends model
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = "carts";
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
                    'payment_status' => $data['payment_status'],
                    'created_at' => $data['created_at']
                ];
            }
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'No carts found'
            ];
        }

        return $dataReturn;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();

            $sql = "INSERT INTO " . $this->tableName . " (id_user, payment_status) VALUES (:id_user, :payment_status)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_user', $params['id_user']);
            $sql->bindValue(':payment_status', $params['payment_status']);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $array = $sql->fetch();
                $dataReturn = [
                    'id' => $this->db->lastInsertId(),
                    'id_user' => $params['id_user'],
                    'payment_status' => $params['payment_status'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
            } else {
                throw new \Exception('User not created');
            }
        } catch (\Exception $e) {
            // Check if message is SQLSTATE to return the message cleaner.
            $dataReturn = $this->checkSQLStateError($e, "Error registering cart");
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
                'payment_status' => $array['payment_status'],
                'active' => $array['active'],
                'created_at' => $array['created_at']
            ];
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'Cart not found'
            ];
        }

        return $dataReturn;
    }

    public function update($params)
    {
        $dataReturn = array();

        $sql = "UPDATE " . $this->tableName . " SET payment_status = :payment_status WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':payment_status', $params['payment_status']);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dataReturn = ['success' => true, 'message' => 'Cart successfully updated'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Cart not updated'];
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
            $dataReturn = ['success' => true, 'message' => 'Cart deleted'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Cart not deleted'];
        }

        return $dataReturn;
    }
}
