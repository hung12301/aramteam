<?php
	
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);
    define('SITE_URL', 'http://mtvip1.com');
    define('FACEBOOK_API', 'https://graph.facebook.com/v2.3/');

    Config::set('DB_HOST', "localhost");
    Config::set('DB_USERNAME', "root");
    Config::set('DB_PASSWORD', "ac@1234tqh");
    Config::set('DB_NAME', 'mt_tool');
    Config::set('DB_CHARSET', 'UTF8');
    Config::set('SSL', false);

    Config::set('password_prefix', 'acdata@123');
?>