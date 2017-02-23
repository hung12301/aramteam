<?php
    
class Route {
    
    protected $route = 'home';
    protected $controller;
    protected $method = 'index';
    protected $params = [];
    static $DB;
    
    public function __construct ($request) {

        $root = explode('/', ROOT);
        $url = explode('/', $request);

        for($i = 0; $i < count($url); $i++) {
            if($url[$i] == $root[count($root) - 1 - $i]) {
                array_shift($url);
            } else {
                break;
            }
        }

        if(count($url) == 0) $url[0] = '';

        if(Config::getSsl($url[0]) && Config::get("SSL")) {
            if(!isset($_SERVER['HTTPS'])) {
                $this->redirect('/' . $request);
            }
        }

        if(!empty(Config::getRoute($url[0]))) {
            $this->route = Config::getRoute($url[0]);
            array_shift($url);
        }

        $temp = explode('-', $this->route);
        $this->route = '';
        foreach ($temp as $value) {
            $this->route .= ucwords($value);
        }
        $controllerName = $this->route . 'Controller';
        $controllerPath = ROOT . '/app/controller/' . $controllerName . '.php';
        require_once $controllerPath;
        $this->controller = new $controllerName;
        
        if(isset($url[0])) {
            
            $method = str_replace("-", "", strtolower($url[0]));

            if(method_exists($this->controller,$method)) {
                $this->method = $method;
                array_shift($url);
            }
        }

        if(!empty($url)) $this->params = array_merge($this->params,$url);

        // Init Database and start use session
        self::$DB = new DB;
        session_start();
        
        // Check remember user
        if(isset($_COOKIE['remember_token']) && isset($_COOKIE['id'])) {
            checkRememberUser();
        }

        // Check refresh
        if(isset($_SESSION['user'])) {
            checkRefresh();
        }

        // RUN
        call_user_func_array([$this->controller,$this->method],$this->params);
    }
    
    public static function back() {
        if(isset($_SERVER['HTTP_REFERER']))
            header('Location:' . $_SERVER['HTTP_REFERER']);
        else
            header('Location:'. SITE_URL);
    }

    public static function redirect ($url) {
        header('Location:' . SITE_URL . $url);
    }
}

?>