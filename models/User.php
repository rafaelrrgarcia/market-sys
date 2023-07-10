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
        $dataReturn = array();

        $sql = "SELECT * FROM " . $this->tableName;
        $sql = $this->db->prepare($sql);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
            foreach ($array as $data) {
                $dataReturn[] = [
                    'id' => $data['id'],
                    'username' => $data['username'],
                    'active' => $data['active'],
                    'created_at' => $data['created_at']
                ];
            }
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'No users found'
            ];
        }

        return $dataReturn;
    }

    public function create($params)
    {
        try {
            $dataReturn = array();

            $sql = "INSERT INTO " . $this->tableName . " (username, password) VALUES (:username, :password)";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':username', $params['username']);
            $sql->bindValue(':password', password_hash($params['password'], PASSWORD_DEFAULT));
            $sql->execute();

            if ($sql->rowCount() > 0) {
                $array = $sql->fetch();
                $dataReturn = [
                    'id' => $this->db->lastInsertId(),
                    'username' => $params['username'],
                    'created_at' => date("Y-m-d H:i:s")
                ];
            } else {
                throw new \Exception('User not created');
            }
        } catch (\Exception $e) {
            // Check if message is SQLSTATE to return the message cleaner.
            $dataReturn = $this->checkSQLStateError($e, "Duplicated username");
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
                'username' => $array['username'],
                'active' => $array['active'],
                'created_at' => $array['created_at']
            ];
        } else {
            $dataReturn = [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        return $dataReturn;
    }

    public function update($params)
    {
        $dataReturn = array();

        $sql = "UPDATE " . $this->tableName . " SET password = :password WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':password', password_hash($params['password'], PASSWORD_DEFAULT));
        $sql->bindValue(':id', $params['id']);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dataReturn = ['success' => true, 'message' => 'User successfully updated'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'User not updated'];
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
            $dataReturn = ['success' => true, 'message' => 'User successfully deleted'];
        } else {
            $dataReturn = ['success' => false, 'message' => 'User not deleted'];
        }

        return $dataReturn;
    }

    public function getValidUserByUsername($username){
        $array = array();
        $return = array();

        $sql = "SELECT * FROM " . $this->tableName . " WHERE username = :username AND active = true";
        $sql = $this->db->prepare($sql);
        $sql->bindValue(':username', $username);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetch();
            $return = [
                'success' => true,
                'data' => [
                    'id' => $array['id'],
                    'username' => $array['username'],
                    'active' => $array['active'],
                    'created_at' => $array['created_at'],
                    'admin' => $array['admin'],
                ]
            ];
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

            $sql = "SELECT * FROM " . $this->tableName . " WHERE username = :username AND active = true";
            $sql = $this->db->prepare($sql);
            $sql->bindValue(':username', $params['username']);
            $sql->execute();
            if ($sql->rowCount() > 0) {
                $array = $sql->fetch();
                if(!password_verify($params['password'], $array['password'])){
                    throw new \Exception('Invalid credentials');
                }

                $dataReturn = [
                    'success' => true,
                    'id' => $array['id']
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