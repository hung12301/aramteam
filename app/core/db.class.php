<?php

class DB {
    
    protected $conn;
    protected $query = null;
    
    public function __construct () {
        $this->conn = mysqli_connect(Config::get('DB_HOST'),Config::get('DB_USERNAME'), Config::get('DB_PASSWORD'),Config::get('DB_NAME'));
        mysqli_set_charset($this->conn,Config::get('DB_CHARSET'));
    }
    
    public function __destruct () {
        mysqli_close($this->conn);
    }
    
   public function is_connected () {
       if(!$this->conn)
           return false;
       
       return true;
   }
    
    public function query ($sql) {

        if(!$this->is_connected())
            return null;
    	
        // fitter sql
        $sql = str_replace("<", "&lt;", $sql);
        $sql = str_replace(">", "&gt;" , $sql);

        $this->query = mysqli_query($this->conn,$sql);
        
        return $this;
    }

    public function fetch () {

        if(!$this->query) {
            return null;
        }
        
        $data = [];
        
        while ($row = mysqli_fetch_assoc($this->query)) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    public function numRows () {
        return mysqli_num_rows($this->query);
    }
    
    public function showError () {
        echo mysqli_error($this->conn);
    }
    
}

?>