<?php //Create post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$email = (isset($_POST['email'])) ? $_POST['email']: '';
$rows = mysql_query("SELECT * FROM ".PREFIX."USER WHERE User_Mail='".$email."'");
if(mysql_num_rows($rows) == 0)
{
	echo('EMAIL_AVAILABLE');
}
else
{
	echo('EMAIL_EXISTS');
}
?>