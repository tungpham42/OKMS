<?php //Set current semester
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['current_semid'])) {
	$current_semid = $_POST['current_semid'];
	set_current_semester($current_semid);
	header('Location: '.$_SERVER['HTTP_REFERER'].'');
}
?>