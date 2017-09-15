<?php //Create post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$email = (isset($_POST['email'])) ? $_POST['email']: '';
$rows = mysqli_query($db->link, "SELECT * FROM ".PREFIX."USER WHERE User_Mail='".$email."'");
if(mysqli_num_rows($rows) == 0)
{
	if (check_mail($email)) {
		echo('EMAIL_AVAILABLE');
	}
}
else
{
	echo('EMAIL_EXISTS');
}
?>