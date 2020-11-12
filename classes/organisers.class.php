<?php

class Organisers {

    private $db;
    private $logs;
    
    public function __construct(PDO $db) {
        $this->logs = new Logs($db);
        $this->db = $db;
    }

    

    public function signup($data) {

        

    }

    private function get_by_id() {
        


    }

}
