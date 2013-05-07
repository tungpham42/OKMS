<?php
require 'libraries/class.database.php';
/* Database config */
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$db = new Database($url["host"],$url["user"],$url["pass"],substr($url["path"],1),'OKMS_');
$db->connect();