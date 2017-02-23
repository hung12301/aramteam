<?php

class Model {
    
    public $table;
    protected $sql;
    
    public function all () {
        $this->sql = "SELECT * FROM " . $this->table;
        return $this->get();
    }

    public function where ($array) {

        $i = 1;
        
        foreach ($array as $key => $value) {

            if($i == 1) {
                $this->sql .= " WHERE $key='$value'";
            } else {
                $this->sql .= " AND $key='$value'";
            }

            $i++;
        }

        $this->sql = rtrim($this->sql);

        return $this;
    }
    
    public function count () {
        return Route::$DB->query($this->sql)->numRows();
    }
    
    public function select ($value) {
        
        if(is_array($value)) {
            
            $this->sql = "SELECT ";
            
            foreach($value as $key=>$field) {
                if($key == count($value) - 1)
                    $this->sql .= $field;
                else
                    $this->sql .= $field . ',';
            }
            
            $this->sql .= " FROM $this->table ";
            
        } else {
            $this->sql = "SELECT {$value} FROM {$this->table}";
        }
        
        return $this;
    }
    
    public function showQuery () {
        echo $this->sql;
    }
    
    public function get () {
        return Route::$DB->query($this->sql)->fetch();
    }

    public function first () {

        if(empty($this->get())) {
            return null;
        }

        return $this->get()[0];
    }

    public function orderBy ($columnName, $type = "ASC") {
        $this->sql .= " ORDER BY " . $columnName . " " . $type;
        return $this;
    }

    public function limit ($number, $offset = null) {

        if($offset != null)
            $this->sql .= " LIMIT " . $offset . "," . $number;
        else
            $this->sql .= " LIMIT " . $number;

        return $this;
    }

    public function offset ($number) {
        $this->sql .= " OFFSET " . $number;
        return $this;
    }
    
    public function insert ($data) {

        $this->sql = "INSERT INTO $this->table (";
        
        foreach ($data as $key => $value) {
            $this->sql .= $key . ',';
        }

        $this->sql .= "updated_at) VALUES (";

        foreach ($data as $key => $value) {
            
            $encode = $value;

            if(!is_numeric($encode))
                $encode = addslashes ($encode);

            $this->sql .= "'$encode',";
        }

        $date = $this->getUpdatedTime();
        $this->sql .= " '$date' )";
        
        return !empty(Route::$DB->query($this->sql));
    }

    public function update ($where, $data, $updateTime = true) {
        $this->sql = "UPDATE $this->table SET ";
        
        $count = 0;
        foreach ($data as $key => $value) {
            $this->sql .= $key . '=' . "'".addslashes($value)."'" . ',';
            $count++;
            if($count == count($data)) $this->sql = rtrim($this->sql, ',');
        }
        
        if($updateTime == true) {
        	$date = $this->getUpdatedTime();
            if(count($data) == 0)
	           $this->sql .= "updated_at=" . "'$date'";
            else
                $this->sql .= ",updated_at=" . "'$date'";
        }
        
        $this->where($where);

        return !empty(Route::$DB->query($this->sql));
    }

    public function delete($id) {
        $this->sql = "DELETE FROM $this->table WHERE id=" . $id;
        return !empty(Route::$DB->query($this->sql));
    }

    public function deleteWith($arr) {
        $this->sql = "DELETE FROM $this->table";
        $this->where($arr);
        return !empty(Route::$DB->query($this->sql));
    }
    
    public function getUpdatedTime () {
        return date("Y-m-d H:i:s");
    }

    public function callModel ($name) {
        
        $modelPath = ROOT . '/app/model/' . $name . '.php';
        
        if(file_exists($modelPath)) {
            require_once $modelPath;
            return new $name;
        } else {
            throw new Exception ('Model' . $name . 'is not exist');
        }
        
    }

    public function like ($columnName, $str ) {
        $this->sql .= " WHERE " . $columnName . " LIKE " . "'%" . $str . "%'"; 
        return $this;

    }
}

?>