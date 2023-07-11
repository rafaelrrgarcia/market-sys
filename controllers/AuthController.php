<?php

class AuthController extends Controller
{
    public function login($params)
    {
        $result = [];
        // Get user from model
        $user = new User();
        $foundUser = $user->checkAuth($params);

        // If user is valid, create token
        if($foundUser['success'] == true){
            $auth = new Auth();
            $response = $auth->createToken(['id' => $foundUser['id'], 'username' => $params['username']]);
            $this->printJson(['success' => true, 'token' => $response]);
        } else {
            $this->printErrorJson($foundUser['message'], 401);
        }
    }

    public function me()
    {
        // Token verification
        $auth = new Auth();
        $this->verifyAuth();

        $this->printJson(['success' => isset($this->loggedUser), 'data' => $this->loggedUser]);
    }
}
