<?php

class Organiser {
    private $db;
    private $logs;
    private $class_name;
    private $class_name_lower;
    
    public function __construct(PDO $db) {
        $this->logs = new Logs($db);
        $this->db = $db;
        $this->class_name = "Organiser";
        $this->class_name_lower = "organiser_class";
    }

    public function get_all() {
        $q = "SELECT * FROM `organisers`";
        $s = $this->db->prepare($q);

        if (!$s->execute()) {
            $failure = $this->class_name.'.get_all - E.02: Failure';
            $this->logs->create($this->class_name_lower, $failure, json_encode($s->errorInfo()));
            return ['status' => false, 'type' => 'query', 'data' => $failure];
        }
        
        if ($s->rowCount() > 0) {
            return ['status' => true, 'type' => 'success', 'data' => $s->fetchAll()];
        }
        return ['status' => false, 'type' => 'empty', 'data' => 'no organisers not found!'];
    }

    public function get_one($column, $value, $compare = "=") {
        $q = "SELECT * FROM `organisers` WHERE `$column` $compare :$column";
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

    /**
     * 
     * @param $data array ["column"=>"value",...]
     * 
     */

    public function add($data) {
        [$cols, $_cols, $vals] = $this->extract_column_value($data);
        $q = "INSERT INTO `organisers`($cols) VALUES ($_cols)";
        $s = $this->db->prepare($q);
        if (!$s->execute($vals)) {
            $failure = $this->class_name.'.add - E.02: Failure';
            $this->logs->create($this->class_name_lower, $failure, json_encode($s->errorInfo()));
            return ['status' => false, 'type' => 'query', 'data' => $failure];
        }
        
        return ['status' => true, 'type' => 'success', 'data' => 'data is successfully inserted.'];
    }


    private function extract_column_value ($data) {
        $cols = "";
        $_cols = "";
        $vals = [];
        $n = 0;
        foreach ($data as $column => $value) {
            $cols .= "`$column`";
            $_cols .= "?";
            array_push($vals, $value);
            if (count($data)-1 > $n++) {
                $cols .= ", ";
                $_cols .= ", ";
            }
        }
        return [$cols, $_cols, $vals];
    }
    
}

