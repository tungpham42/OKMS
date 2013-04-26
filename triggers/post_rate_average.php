<?php //Show average rating
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid'])) {
	$pid = $_POST['pid'];
	print star_rating_average($pid);
}
?>