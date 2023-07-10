<?php

class Sale extends model
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = "sales";
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
                    'id_cart' => $data['id_cart'],
                    'total_price_products' => $data['total_price_products'],
                    'total_price_taxes' => $data['total_price_taxes'],
                    'final_price' => $data['final_price'],
                    'created_at' => $data['created_at']
                ];
            }
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'No sales found'
            ];
        }

        return $dataReturn;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();

            $sql = "INSERT INTO " . $this->tableName . " (id_cart, total_price_products, total_price_taxes, final_price) VALUES (:id_cart, :total_price_products, :total_price_taxes, :final_price)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':id_cart', $params['id_cart']);
            $sql->bindValue(':total_price_products', $params['total_price_products']);
            $sql->bindValue(':total_price_taxes', $params['total_price_taxes']);
            $sql->bindValue(':final_price', $params['final_price']);
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $array = $sql->fetch();
                $dataReturn = [
                    'id' => $this->db->lastInsertId(),
                    'id_cart' => $params['id_cart'],
                    'total_price_products' => $params['total_price_products'],
                    'total_price_taxes' => $params['total_price_taxes'],
                    'final_price' => $params['final_price'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
            } else {
                throw new \Exception('Sale not created');
            }
        } catch (\Exception $e) {
            // Check if message is SQLSTATE to return the message cleaner.
            $dataReturn = $this->checkSQLStateError($e, "Error creating the new sale");
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
                'id_cart' => $array['id_cart'],
                'total_price_products' => $array['total_price_products'],
                'total_price_taxes' => $array['total_price_taxes'],
                'final_price' => $array['final_price'],
                'active' => $array['active'],
                'created_at' => $array['created_at']
            ];
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'Sale not found'
            ];
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
            $dataReturn = ['success' => true, 'message' => 'Sale deleted'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Sale not deleted'];
        }

        return $dataReturn;
    }
}
