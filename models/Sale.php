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
        $return = array();

        $sql = "SELECT * FROM " . $this->tableName . " WHERE active = true ORDER BY created_at DESC";
        $sql = $this->db->prepare($sql);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $return['success'] = true;
            $array = $sql->fetchAll();
            foreach ($array as $data) {
                $return['data'][] = $this->setReturnFields([
                    'id',
                    'id_user',
                    'product_name',
                    'total_price_products',
                    'total_price_taxes',
                    'final_price',
                    'quantity',
                    'created_at'
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

    public function create($id_user, $sales)
    {
        try {
            $return = array();
            
            if(count($sales) <= 0) throw new \Exception('No sales found');
            
            // Begin transaction
            $this->db->beginTransaction();

            // Foreach sales from sales
            foreach ($sales as $sale) {
                $sql = "INSERT INTO " . $this->tableName . " (
                        id_user, product_name, total_price_products, total_price_taxes, final_price, quantity
                    ) VALUES ( 
                        :id_user, :product_name, :total_price_products, :total_price_taxes, :final_price, :quantity
                    )";
                $sql = $this->db->prepare($sql);
                $sql->bindValue(':id_user', $id_user);
                $sql->bindValue(':product_name', $sale['product_name']);
                $sql->bindValue(':total_price_products', $sale['total_price_products']);
                $sql->bindValue(':total_price_taxes', $sale['total_price_taxes']);
                $sql->bindValue(':final_price', $sale['final_price']);
                $sql->bindValue(':quantity', $sale['quantity']);
                $sql->execute();
            }

            $return['success'] = true;

            // Commit transaction
            $this->db->commit();
        } catch (\Exception $e) {
            // Check if begins a transaction
            if ($this->db->inTransaction()) {
                // Rollback transaction
                $this->db->rollBack();
            }
            // Check if message is SQLSTATE to return the message cleaner.
            $return['success'] = false;
            $return = $this->checkSQLStateError($e, "Sales error.");
        } finally {
            return $return;
        }
    }

    public function read($params)
    {
        $array = array();
        $return = array();

        $sql = "SELECT * FROM " . $this->tableName . " WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetch();
            $return['success'] = true;
            $return['data'] = $this->setReturnFields([
                'id',
                'product_name',
                'total_price_products',
                'total_price_taxes',
                'final_price',
                'quantity',
                'created_at'
            ], $array);
        } else {
            $return = [
                'success' => false,
                'message' => 'Sale not found'
            ];
        }

        return $return;
    }

    public function delete($params)
    {
        $dataReturn = array();

        $sql = "UPDATE " . $this->tableName . " SET active = false WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $params['id']);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dataReturn = ['success' => true, 'message' => 'Sale successfully deleted'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Sale not deleted'];
        }

        return $dataReturn;
    }
}
