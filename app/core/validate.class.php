<?php

class Validate {
    
    public static function isEmail ($string) {
        return preg_match('/[A-z0-9\-]+@([A-z0-9\-]+)(\.[A-z0-9]+){1,2}/im', $string);
    }
    
    public static function isCharacter ($string) {
        return preg_match('/[A-z\sÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]+/im', $string);
    }

    public static function isUrlYoutube ($str) {
    	return preg_match ('/https?:\/\/(?:youtu\.be\/|(?:[a-z]{2,3}\.)?youtube\.com\/watch(?:\?|#\!)v=)([\w-]{11}).*/i', $str);
    }

    public static function checkUser ($rule) {

    	$arr = explode(",", $rule);

    	foreach ($arr as $str) {

    		if($str == 'login') {
                if(!isset($_SESSION['user'])) return false;
    		}

    		if($str == 'expired_vip1') {
    			// get renew
	    		$userID = $_SESSION['user']['id'];
	    		$license = Route::$DB->query("SELECT * FROM license WHERE user_id=$userID AND type='vip1' ORDER BY id DESC LIMIT 1")->fetch();
                if(empty($license)) return false;
                $license = $license[0];
                if($license['forever'] == 1) return true;
	    		if(strtotime($license['expried_at']) < time()) {
	    			return false;
	    		}
    		}
    	}

    	return true;
    }
    
}

?>