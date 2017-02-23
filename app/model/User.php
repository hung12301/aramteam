<?php

class User extends Model {
    
    public $table = 'users';
    
    public function login ($email,$password) {
        $data = $this->select("password")->where(['email'=>$email])->first();
        return $password == $data['password'];
    }
}

?>