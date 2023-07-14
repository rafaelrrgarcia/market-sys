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
        $return = array();
        $return['success'] = false;
        $joinedTable = ProductType::getTableName();

        // Set joins
        $this->joins = ['JOIN '.$joinedTable.' ON '.$this->tableName.'.id_type = '.$joinedTable.'.id'];

        // Return list of products from ORM
        $sql = $this->select(
            [
                $this->tableName.'.id',
                $this->tableName.'.name as productname',
                $this->tableName.'.value as productvalue',
                $joinedTable.'.name as type',
                $joinedTable.'.tax',
                '('.$this->tableName.'.value * '.$joinedTable.'.tax) as taxvalue',
                '(('.$this->tableName.'.value * '.$joinedTable.'.tax) + '.$this->tableName.'.value) as totalvalue'
            ],[
                $this->tableName.'.active = true'
            ],[
                $this->tableName.'.created_at DESC'
            ]
        );


        if ($sql['success']) {
            $return['data'] = $sql['data'];
            $return['success'] = true;
        } else {
            $return = [
                'success' => false,
                'message' => 'No products found'
            ];
        }

        return $return;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();
            $dataReturn['success'] = false;

            // Insert new product in ORM
            $sql = $this->insert([
                'id_type' => $params['id_type'],
                'name' => $params['name'],
                'value' => $params['value']
            ]);

            if ($sql['success']) {
                $dataReturn['success'] = true;
                $dataReturn['data'] = [
                    'id' => $sql['data']['id'],
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
        $return = array();
        $return['success'] = false;
        $joinedTable = ProductType::getTableName();

        // Set joins
        $this->joins = ['JOIN '.$joinedTable.' ON '.$this->tableName.'.id_type = '.$joinedTable.'.id'];

        // Return list of products from ORM
        $sql = $this->select(
            [
                $this->tableName.'.id',
                $this->tableName.'.name as productname',
                $this->tableName.'.value as productvalue',
                $this->tableName.'.id_type',
                $joinedTable.'.name as type',
                $joinedTable.'.tax',
                '('.$this->tableName.'.value * '.$joinedTable.'.tax) as taxvalue',
                '(('.$this->tableName.'.value * '.$joinedTable.'.tax) + '.$this->tableName.'.value) as totalvalue'
            ],[
                $this->tableName.'.active = true',
                $this->tableName.'.id = '.$params['id'],
            ],[
                $this->tableName.'.created_at DESC'
            ],[1]
        );


        if ($sql['success'] && count($sql['data']) > 0) {
            $return['data'] = $sql['data'];
            $return['success'] = true;
        } else {
            $return = [
                'success' => false,
                'message' => 'No product found'
            ];
        }

        return $return;
    }

    public function modify($params)
    {
        $dataReturn = array();

        // Update product in ORM
        $sql = $this->update([
            'id_type' => $params['id_type'],
            'name' => $params['name'],
            'value' => $params['value']
        ],[
            'id' => $params['id']
        ]);

        if ($sql['success']) {
            $dataReturn['success'] = true;
            $dataReturn['data'] = [
                'id' => $params['id'],
                'id_type' => $params['id_type'],
                'name' => $params['name'],
                'value' => $params['value'],
                'created_at' => date("Y-m-d H:i:s")
            ];
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'Product not updated'
            ];
        }

        return $dataReturn;
    }

    public function delete($params)
    {
        $dataReturn = array();
        $sql = $this->update(['active' => 'false'], ['id' => $params['id']]);
        if ($sql['success']) {
            $dataReturn = ['success' => true, 'message' => 'Product successfully removed'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Product not removed'];
        }
        return $dataReturn;
    }
}
