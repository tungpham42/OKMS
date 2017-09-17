<?php
require 'libraries/class.database.php';
/* Database config */
$url = getenv('JAWSDB_URL');
$dbparts = parse_url($url);
$hostname = $dbparts['host'];
$username = $dbparts['user'];
$password = $dbparts['pass'];
$database = ltrim($dbparts['path'],'/');
$db = new Database($hostname, $username, $password, $database,'OKMS_');
$db->connect();
