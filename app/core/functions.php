<?php 
	
	function createRememberToken () {
		return md5(time() . 'acdata@123') . md5(rand(1,10000000000));
	}
	
	function checkRememberUser () {

		$id = $_COOKIE['id'];
		$rememberToken = $_COOKIE['remember_token'];

		// Get info of user with $id
		$user = Route::$DB->query("SELECT * FROM users WHERE id=$id")->fetch()[0];

		if($rememberToken == $user['remember_token']) {
			// Set session is logged in
			$_SESSION['user'] = $user;
		}
	}

	function checkRefresh() {
		$id = $_SESSION['user']['id'];

		$user = Route::$DB->query("SELECT * FROM users WHERE id=$id")->fetch()[0];
		
		if($user['refresh'] == 1) {
			Route::$DB->query("UPDATE users SET refresh=0 WHERE id=$id");
			$_SESSION['user'] = $user;
		}
	}

	function getCharTime ($time) {

		$current = time();

		$thoiGian = [
			[
				'timemax' => 60,
				'name' => " giây trước"
			],
			[
				'timemax' => 3600,
				'name' => " phút trước"
			],
			[
				'timemax' => 86400,
				'name' => " giờ trước"
			],
			[
				'timemax' => 172800,
				'name' => "Hôm qua lúc "
			],
			[
				'timemax' => 432000,
				'name' => " ngày trước"
			],
		];

		foreach ($thoiGian as $key => $value)
		{
			if (($current-$time)<$value['timemax'])
			{
				if ($value['timemax']==172800)
				{
					return $value['name'] . date("H:i:s", $time);
				}

				if($key == 0)
					return ($current-$time) . $value['name'];
				else
					return round(($current-$time) / $thoiGian[$key - 1]['timemax']) . $value['name'];
			}
		}


		return date("d/m/Y H:i:s",$time);
	}

	function getIP () {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    return $ipaddress;
	}



	function replaceVNChar($str) {

	     $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	     $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	     $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	     $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	     $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	     $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
	     $str = preg_replace("/(đ)/", 'd', $str);    

	     $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
	     $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
	     $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
	     $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
	     $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	     $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	     $str = preg_replace("/(Đ)/", 'D', $str);
	     return $str; 
	}  

	function replaceUrlName ($str)
	{
	  $chuoi =  replaceVNChar ($str);

	  $str = str_replace(' ', '-', $chuoi);

	  return strtolower($str);
	}

	function countRateStar ($data) {
		$tong = 0;
		$bien = 0;

		for ($i=0; $i<5; $i++){
			$tong = $tong + $data[$i];
		}

		for ($i=0; $i<5;$i++){
			$data['percent'][$i] = (float) ($data[$i]/$tong)*100;
			$bien = $bien + $data[$i]*($i+1);
		}

		$bien1 = (float) $bien/$tong;
		$bien2 = ceil ($bien/$tong);

		if ($bien2==$bien1) {
			$data['type'] = 'full';
			$data['value'] = $bien2;
		} else {
			if ($bien2-$bien1>=0.6) {
				$data['type'] = 'full';
				$data['value'] = $bien2-1;
			} else {
				$data['type'] = 'half';
				$data['value'] = $bien2;
			}

		}

		return $data;
	}

	function convertPostInfo ($data) {
		$postinfo = '';
		foreach ($data as $key => $value) {
			$postinfo .= $key . '=' . urlencode($value) . '&';
		}
		return substr($postinfo, 0, strlen($postinfo) - 1);
	}

	function curl ($url, $post = null, $header = null, $cookies = null,  $agent = "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/60.4.136 Chrome/54.4.2840.136 Safari/537.36") {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);

		if($post != null) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}

		if($header != null) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		if($cookies != null ) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
		}

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 

		$result = curl_exec($ch);  // grab URL and pass it to the variable.
		curl_close($ch);  // close curl resource, and free up system resources.

		return $result; // Print page contents.
	}

	function curl_delete($url, $json='') {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
	    $result = curl_exec($ch);
	    $result = json_decode($result);
	    curl_close($ch);
	    return $result;
	}

	function getPostIdByUrl ($url) {
		preg_match('/permalink\/([0-9]*)(\/\?){0,1}/im', $url, $match);
		if(isset($match[1])) return $match[1];
		preg_match('/&id=([0-9]*)&pnref=story/im',$url, $match);
		if(isset($match[1])) return $match[1];
		preg_match('/posts\/([0-9]*)/im', $url, $match);
		if(isset($match[1])) return $match[1];
	}
?>