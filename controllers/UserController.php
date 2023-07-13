<?php

class UserController extends Controller
{
    public function __construct()
    {
        // Only authenticated admins can handle users
        $this->setPermissions(['auth', 'admin']);
    }

    public function index($params)
    {
        // Model actions
        $users = new User();
        $foundUsers = $users->index();
        if($foundUsers['success'])
            $this->printJson($foundUsers);
        else 
            $this->printErrorJson($foundUsers['message'], 400);
    }

    public function create($params)
    {

        // Check required fields
        $requiredFields = ['username', 'password'];
        foreach ($requiredFields as $field) {
            if (!isset($params[$field]) || $params[$field] == '')
                $this->printEmptyFieldJson($field);
        }

        // Model actions
        $users = new User();
        $foundUser = $users->create($params);
        if($foundUser['success'])
            $this->printJson($foundUser, 201);
        else 
            $this->printErrorJson($foundUser['message'], 400);
    }

    public function read($params)
    {
        // Validations
        if (!isset($params['id']) || $params['id'] == '')
            $this->printEmptyFieldJson('Id');

        // Model actions
        $users = new User();
        $foundUser = $users->read($params);
        if($foundUser['success'])
            $this->printJson($foundUser);
        else 
            $this->printErrorJson($foundUser['message'], 400);
    }

    public function modify($params)
    {
        // Validations
        if (!isset($params['password']) || $params['password'] == '')
            $this->printEmptyFieldJson('Password');

        // Model actions
        $users = new User();
        $foundUser = $users->modify($params);
        if($foundUser['success'])
            $this->printJson($foundUser);
        else 
            $this->printErrorJson($foundUser['message'], 400);
    }

    public function delete($params)
    {
        // Validations
        if (!isset($params['id']) || $params['id'] == '')
            $this->printEmptyFieldJson('Id');

        // Model actions
        $users = new User();
        $foundUser = $users->delete($params);
        if($foundUser['success'])
            $this->printJson($foundUser);
        else 
            $this->printErrorJson($foundUser['message'], 400);
    }
}
