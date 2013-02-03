<?php //Create post
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
$username = (isset($_POST['username'])) ? $_POST['username']: '';
$rows = mysql_query("SELECT * FROM ".PREFIX."USER WHERE User_Username='".$username."'");
if(mysql_num_rows($rows) == 0)
{
	echo('USERNAME_AVAILABLE');
}
else
{
	echo('USERNAME_EXISTS');
}
?>