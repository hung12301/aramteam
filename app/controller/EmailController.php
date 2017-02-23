<?php
	class EmailController extends Controller
	{
		public static function getNewEmail () {
			$header = [
				':authority'=>'10minutemail.com',
				':method'=>'GET',
				':path'=>'/10MinuteMail/resources/session/address',
				':scheme'=>'https',
				'accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
				'accept-encoding'=>'gzip, deflate, sdch, br',
				'accept-language'=>'vi-VN,vi;q=0.8,fr-FR;q=0.6,fr;q=0.4,en-US;q=0.2,en;q=0.2',
				'upgrade-insecure-requests'=>'1',
				'cache-control'=>'max-age=0',
				'user-agent'=>'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/60.4.136 Chrome/54.4.2840.136 Safari/537.36',
				'cookie'=>'JSESSIONID=3ZxX2g6sfDyUs1P2JVML2u_X47ZTWW8rZCape7P0.syndi; __cfduid=d5220e3a714845405847a0f8d4ba8738a1486166710; _gat=1; _ga=GA1.2.1386550736.1486166711',

			];
			$str = curl('https://10minutemail.com/10MinuteMail/resources/session/address',null,$header,true);
			echo $str;
		}

		public static function viewEmail ($id) {
			$str = curl('10minutemail.com/10MinuteMail/index.html?dswid=' . $id);
			echo $str;
		}
	}

?>