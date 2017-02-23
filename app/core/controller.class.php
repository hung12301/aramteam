<?php

class Controller {
    
    public function view ($name, $data = null) {
        
        $viewPath = ROOT . '/app/view/' . $name . '.php';
        
        if(file_exists($viewPath)) {
            require_once $viewPath;
            return true;
        }
        
        return false;
    }
    
    public function model ($name) {
        
        $modelPath = ROOT . '/app/model/' . $name . '.php';
        
        if(file_exists($modelPath)) {
            require_once $modelPath;
            return new $name;
        } else {
            throw new Exception ('Model' . $name . 'is not exist');
        }
        
    }
    
}

?>