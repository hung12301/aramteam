<?php

class Session {
    
    public static function setFlash ($type,$message) {
        $_SESSION['flash'] = array('type' => $type, 'message'=> $message);
    }
    
    public static function hasFlash () {
        return isset($_SESSION['flash']);
    }
    
    public static function getFlash () {
        
        $data['type'] = $_SESSION['flash']['type'];
        $data['message'] = $_SESSION['flash']['message'];
        unset($_SESSION['flash']);
        
        return $data;
    }
    
}

?>