<?php

class Country {
    private $db;
    private $logs;
    private $class_name;
    private $class_name_lower;
    private $table_name;
    
    public function __construct(PDO $db) {
        $this->logs = new Logs($db);
        $this->db = $db;
        $this->class_name = "Country";
        $this->class_name_lower = "country_class";
        $this->table_name = "countries";
    }

    public function get_one($column, $value, $compare = "=") {
        $q = "SELECT * FROM `".$this->table_name."` WHERE `$column` $compare :$column";
        $s = $this->db->prepare($q);
        $s->bindParam(":$column", $value);

        if (!$s->execute()) {
            $failure = $this->class_name.'.get_one - E.02: Failure';
            $this->logs->create($this->class_name_lower, $failure, json_encode($s->errorInfo()));
            return ['status' => false, 'type' => 'query', 'data' => $failure];
        }
        
        if ($s->rowCount() > 0) {
            return ['status' => true, 'type' => 'success', 'data' => $s->fetch()];
        }
        return ['status' => false, 'type' => 'empty', 'data' => 'no organiser not found!'];
    }
    
}
