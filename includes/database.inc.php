<?php
require 'libraries/class.database.php';
/* Database config */
$db = new Database('localhost','username','password','database','OKMS_');
$db->connect();