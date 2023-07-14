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

        $return['success'] = false;

        // Select all sales in ORM
        $sql = $this->select([], [], ['id DESC']);
        if ($sql['success']) {
            $array = $sql['data'];
            foreach ($array as $data) {
                $return['data'][] = $this->setReturnFields([
                    'id',
                    'id_user',
                    'product_name',
                    'total_price_products',
                    'total_price_taxes',
                    'final_price',
                    'created_at'
                ], $data);
            }
            $return['success'] = true;
        } else {
            $return = [
                'success' => false,
                'message' => 'No sales found'
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
                // Create by ORM the new sale
                $sql = $this->insert([
                    'id_user' => $id_user,
                    'product_name' => $sale['product_name'],
                    'total_price_products' => $sale['total_price_products'],
                    'total_price_taxes' => $sale['total_price_taxes'],
                    'final_price' => $sale['final_price']
                ]);
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
        // get sale by ID from ORM
        $sql = $this->select([], ['id = ' . $params['id']]);
        die(json_encode($sql));
        if ($sql['success']) {
            $array = $sql['data'];
            foreach ($array as $data) {
                $return['data'][] = $this->setReturnFields([
                    'id',
                    'id_user',
                    'product_name',
                    'total_price_products',
                    'total_price_taxes',
                    'final_price',
                    'created_at'
                ], $data);
            }
            $return['success'] = true;
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
        $sql = $this->update(['active' => 'false'], ['id' => $params['id']]);
        if ($sql['success']) {
            $dataReturn = ['success' => true, 'message' => 'Sale successfully removed'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Sale not removed'];
        }
        return $dataReturn;
    }
}
