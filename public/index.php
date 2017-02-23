<?php
	// Show Error
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Set time zone
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    require_once "../app/init.php";
    $URI = trim($_SERVER['REQUEST_URI'],'/');
    $routes = new Route($URI);
?>