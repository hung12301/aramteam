<?php
class Config {
    
    protected static $setting = [];
    protected static $route = [];
    protected static $ssl = [];
    
    public static function setRoute($key,$value,$ssl = false) {
        self::$route[$key] = $value;
        self::$ssl[$key] = false;
        if($ssl == true) self::$ssl[$key] = true;
    }
    
    public static function getRoute($key) {
        if(isset(self::$route[$key])) return self::$route[$key];
        return null;
    }

    public static function getSsl ($key) {
        if(isset(self::$ssl[$key])) return self::$ssl[$key];
        return null;
    }
    
    public static function set($key,$value) {
        self::$setting[$key] = $value;
    }
    
    public static function get($key) {
        if(isset(self::$setting[$key]))
            return self::$setting[$key];
        return null;
    }
}
?>