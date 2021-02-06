<?php

require 'jwthandler.class.php';

class Auth extends JWTHandler {

    private $db;
    private $logs;
    private $class_name;
    private $class_name_lower;
    protected $token;
    private $headers;

    public function __construct(PDO $db, $headers) {
        parent::__construct();
        $this->logs = new Logs($db);
        $this->db = $db;
        $this->class_name = "Auth";
        $this->class_name_lower = "auth_class";
        $this->headers = $headers;
    }

    public function isAuth_organiser () {

        if(array_key_exists('Authorization',$this->headers) && !empty(trim($this->headers['Authorization']))) {

            $this->token = explode(" ", trim($this->headers['Authorization']));
            
            if (isset($this->token[1]) && !empty(normal_text($this->token[1]))) {

                $data = $this->jwt_decode_data($this->token[1]);

                if(isset($data['auth']) && isset($data['data']->organiser_id) && $data['auth']) {
                    
                    $organiser = new Organiser($this->db);
                    $get_organiser = $organiser->get_one('or_id', $data['data']->organiser_id);

                    if ($get_organiser['status']) {
                        return ["status" => true, "type" => "success", "data" => "User is valid"];
                    } else {
                        return $get_organiser;
                    }

                } else {
                    return ["status" => false, "type" => "invalid", "data" => "Provided token is invalid."];
                }

            } else {
                return ["status" => false, "type" => "empty", "data" => "Token in the header is empty."];
            }


        } else {
            return ["status" => false, "type" => "missing", "data" => "Token in the header is missing."];
        }
        
    }

}
