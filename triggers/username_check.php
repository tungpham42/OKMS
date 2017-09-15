<?php //Create post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$username = (isset($_POST['username'])) ? $_POST['username']: '';
$rows = mysqli_query($db->link, "SELECT * FROM ".PREFIX."USER WHERE User_Username='".$username."'");
if(mysqli_num_rows($rows) == 0)
{
	echo('USERNAME_AVAILABLE');
}
else
{
	echo('USERNAME_EXISTS');
}
?>