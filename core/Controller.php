<?php

class Controller
{
    protected $loggedUser = null;

    public function printJson($dataToJson, $code = 200)
    {
        http_response_code($code);
        echo json_encode($dataToJson);
        exit;
    }

    public function printEmptyFieldJson($fieldName)
    {
        $message = [
            'success' => false,
            'message' => 'Missing field: ' . $fieldName
        ];
        http_response_code(400);
        echo json_encode($message);
        exit;
    }

    public function printErrorJson($errorMessage, $errorCode = 500)
    {
        $message = [
            'success' => false,
            'message' => 'Error: ' . $errorMessage
        ];
        http_response_code($errorCode);
        echo json_encode($message);
        exit;
    }

    protected function verifyAuth()
    {
        try {
            $return = [];
            // get JWT token from Header
            $headers = getallheaders(); 
            // -- Check token and auth
            $auth = new Auth();
            $auth = $auth->verifyToken(@$headers['Authorization']);
            if(!$auth['success']) throw new Exception($auth['message']);

            // -- Get user infos by username
            $user = new User();
            $user = $user->getValidUserByUsername($auth['data']->username);
            if(!$user['success']) throw new Exception($user['message']);

            $this->loggedUser = $user['data'][0];
            $return = ['success' => true];
        } catch (\Exception $e) {
            $return = ['success' => false, 'message' => $e->getMessage()];
        } finally {
            return $return;
        }
    }

    protected function setPermissions($permissions = []){
        if(!empty($permissions)){
            $auth = $this->verifyAuth();
            foreach ($permissions as $permission) {
                switch ($permission) {
                    case 'auth':
                        if(!$auth['success']) $this->printErrorJson($auth['message'], 401);
                        break;
                    case 'admin':
                        if(!$this->loggedUser['admin']) 
                            $this->printErrorJson("You don't have permission to access this endpoint", 401);
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
