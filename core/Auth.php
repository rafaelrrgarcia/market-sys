<?php

class Auth
{
    public function createToken($extraParams = []){
        // Make header
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $header = json_encode($header);
        $header = base64_encode($header);

        // Make payload
        $payload = [
            'iss' => URL,
            'aud' => URL,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7)
        ];

        // Merge with extra params
        $payload = array_merge($payload, $extraParams);

        // Encode payload
        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        // Encode payload
        $signature = hash_hmac('sha256', $header . "." . $payload, AUTH_KEY, true);
        $signature = base64_encode($signature);

        // Returns JWT token
        return $header . "." . $payload . "." . $signature;
    }

    public function verifyToken($token = '') {
        try {
            $validSignature = false;
            $validDate = false;
            $return = [];
            $return['success'] = false;
            if(!$token || $token == ''){
                throw new \Exception("Missing token");
            }
            // Convert JWT token to Array
            $jwtToken = explode(".", $token);

            // Separate JWT token parts 
            $jwtHeader = $jwtToken[0];
            $jwtPayload = $jwtToken[1];
            $jwtSignature = $jwtToken[2];

            // CHECK SIGNATURE
            $validateSignature = hash_hmac('sha256', $jwtHeader . "." . $jwtPayload, AUTH_KEY, true);
            $validateSignature = base64_encode($validateSignature);
            if($jwtSignature == $validateSignature){
                $validSignature = true;
            }

            // CHECK DATE
            $payloadData = base64_decode($jwtPayload);
            $payloadData = json_decode($payloadData);
            if($payloadData->exp > time()){
                $validDate = true;
            }

            if ($validSignature && $validDate) {
                $return = ['success' => true, 'data' => $payloadData];
            } else {
                throw new \Exception("Invalid token");
            }
        } catch (\Exception $e) {
            $return = ['success' => false, 'message' => $e->getMessage()];
        } finally {
            return $return;
        }

        

        
    }
}
