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

        // Select all types in ORM
        $sql = $this->select([], [], ['id DESC']);
        if ($sql['success']) {
            $array = $sql['data'];
            foreach ($array as $data) {
                $dataReturn['data'][] = $this->setReturnFields([
                    'id',
                    'name',
                    'tax',
                    'created_at'
                ], $data);
            }
            $dataReturn['success'] = true;
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'No types found'
            ];
        }

        return $dataReturn;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();
            $dataReturn['success'] = false;

            // Insert new type in ORM
            $sql = $this->insert([
                'name' => $params['name'],
                'tax' => $params['tax']
            ]);

            if ($sql['success']) {
                $dataReturn['success'] = true;
                $dataReturn['data'] = [
                    'id' => $this->db->lastInsertId(),
                    'name' => $params['name'],
                    'tax' => $params['tax'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
            } else {
                throw new \Exception('Type not created');
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

        // Select type in ORM
        $sql = $this->select(
            [],
            [
                'id = ' . $params['id']
            ]
        );

        if ($sql['success']) {
            $array = $sql['data'];
            $dataReturn['data'] = $this->setReturnFields([
                'id',
                'name',
                'tax',
                'created_at'
            ], $array[0]);
            $dataReturn['success'] = true;
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

        // Update type in ORM
        $sql = $this->update([
            'name' => $params['name'],
            'tax' => $params['tax']
        ],[
            'id' => $params['id']
        ]);

        if ($sql['success']) {
            $dataReturn = ['success' => true, 'message' => 'Type successfully updated'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Type not updated'];
        }

        return $dataReturn;
    }

    public function delete($params)
    {
        $dataReturn = array();
        $sql = $this->update(['active' => 'false'], ['id' => $params['id']]);
        if ($sql['success']) {
            $dataReturn = ['success' => true, 'message' => 'Product type successfully removed'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'Product type not removed'];
        }
        return $dataReturn;
    }
}
