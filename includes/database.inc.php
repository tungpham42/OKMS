<?php
require 'libraries/class.database.php';
/* Database config */
$url = array(
	'host' => '127.0.0.1',
	'user' => 'nhipsinh_okms',
	'pass' => '0KM$v0d0i',
	'db' => 'nhipsinh_okms'
);
$db = new Database($url["host"],$url["user"],$url["pass"],$url["db"],'OKMS_');
$db->connect();