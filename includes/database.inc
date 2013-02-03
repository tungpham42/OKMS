<?php
/* Database config */

$db_host		= 'localhost';
$db_user		= 'tungpham_okms';
$db_pass		= 'OKMSpa$$1';
$db_database	= 'tungpham_main';

/* End config */

$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');

mysql_select_db($db_database,$link);
mysql_query("SET names UTF8");