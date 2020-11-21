<?php

require DIR.'vendor/php-jwt/JWT.php';
require DIR.'vendor/php-jwt/ExpiredException.php';
require DIR.'vendor/php-jwt/SignatureInvalidException.php';
require DIR.'vendor/php-jwt/BeforeValidException.php';

use Firebase\JWT\JWT;

class JWTHandler {

    protected $jwt_secrect;
    protected $token;
    protected $issuedAt;
    protected $expire;
    protected $jwt;

    public function __construct () {
        $this->issuedAt = time();
        $this->expire = $this->issuedAt + SESSION_EXPIRE_TIME;
        $this->jwt_secrect = SECRET;  
    }


    public function jwt_encode_data ($iss,$data) {
        $this->token = array(
            "iss" => $iss,
            "aud" => $iss,
            "iat" => $this->issuedAt,
            "exp" => $this->expire,
            "data"=> $data
        );

        $this->jwt = JWT::encode($this->token, $this->jwt_secrect);
        return $this->jwt;
    }


    public function jwt_decode_data ($jwt_token) {
        try{
            $decode = JWT::decode($jwt_token, $this->jwt_secrect, array('HS256'));
            return [
                "auth" => 1,
                "data" => $decode->data
            ];
        }
        catch(\Firebase\JWT\ExpiredException $e){
            return $this->_error_getter($e->getMessage());
        }
        catch(\Firebase\JWT\SignatureInvalidException $e){
            return $this->_error_getter($e->getMessage());
        }
        catch(\Firebase\JWT\BeforeValidException $e){
            return $this->_error_getter($e->getMessage());
        }
        catch(\DomainException $e){
            return $this->_error_getter($e->getMessage());
        }
        catch(\InvalidArgumentException $e){
            return $this->_error_getter($e->getMessage());
        }
        catch(\UnexpectedValueException $e){
            return $this->_error_getter($e->getMessage());
        }
    }

    private function _error_getter ($message) {
        return [
            "status" => false,
            "message" => $message
        ];
    }

}
