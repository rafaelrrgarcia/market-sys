<?php

class User extends model
{
    function __construct()
    {
        parent::__construct();
        $this->tableName = "users";
    }

    public function index()
    {
        $array = array();
        $return = array();
        $return['success'] = false;
        
        $getFields = ['id', 'username', 'active', 'created_at'];
        // select from ORM
        $sql = $this->select($getFields);
        if ($sql['success']) {
            $array = $sql['data'];
            foreach ($array as $data) {
                $return['data'][] = $this->setReturnFields($getFields, $data);
            } 
            $return['success'] = true;
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
            // Create by ORM the new user
            $sql = $this->insert([
                'username' => $params['username'],
                'password' => password_hash($params['password'], PASSWORD_DEFAULT)
            ]);

            if ($sql['success']) {
                $return = [
                    'success' => true,
                    'data' => [
                        'id' => $this->db->lastInsertId(),
                        'username' => $params['username'],
                        'created_at' => date("Y-m-d H:i:s")
                    ]
                ];
            } else {
                throw new \Exception('User not created');
            }
        } catch (\Exception $e) {
            // Check if message is SQLSTATE to return the message cleaner.
            $return = $this->checkSQLStateError($e, "Duplicated username");
        } finally {
            return $return;
        }
    }

    public function read($params)
    {
        $array = array();
        $return = array();
        $getParams = ['id', 'username', 'active', 'created_at'];

        // Select from ORM by id
        $sql = $this->select($getParams, ['id = ' . $params['id']], [], [1]);
        if ($sql['success']) {
            $return = $sql;
        } else {
            $return = [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        return $return;
    }

    public function modify($params)
    {
        $dataReturn = array();
        $sql = $this->update(['password' => password_hash($params['password'], PASSWORD_DEFAULT)], ['id' => $params['id']]);
        if ($sql['success']) {
            $dataReturn = ['success' => true, 'message' => 'User successfully updated'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'User not updated'];
        }
        return $dataReturn;
    }

    public function delete($params)
    {
        $dataReturn = array();
        $sql = $this->update(['active' => 'false'], ['id' => $params['id']]);
        if ($sql['success']) {
            $dataReturn = ['success' => true, 'message' => 'User successfully removed'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'User not removed'];
        }
        return $dataReturn;
    }

    public function getValidUserByUsername($username){
        $array = array();
        $return = array();
        
        // Select valid user from ORM by username
        $sql = $this->select(['id', 'username', 'active', 'created_at', 'admin'], ["username = '".$username."'", "active = true"], [], [1]);
        if ($sql['success']) {
            $return = $sql;
        } else {
            $return = [
                'success' => false,
                'message' => 'User not found'
            ];
        }
        return $return;
    }

    public function checkAuth($params){
        try {
            // Get user by username and active in the params
            $array = array();
            $dataReturn = array();

            $sql = $this->select([], ["username = '".$params['username']."'", "active = true"]);
            if ($sql['success']) {
                $array = $sql['data'];
                if(!password_verify($params['password'], $array[0]['password'])){
                    throw new \Exception('Invalid credentials');
                }

                $dataReturn = [
                    'success' => true,
                    'id' => $array[0]['id'],
                    'admin' => $array[0]['admin']
                ];
            } else {
                throw new \Exception('User not found');
            }
        } catch (\Exception $e) {
            $dataReturn = [
                'success' => false,
                'message' => $e->getMessage()
            ];
        } finally {
            return $dataReturn;
        }
    }
}
