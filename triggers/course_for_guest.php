<?php //Allow or disallow course post
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['cid'])) {
	$cid = $_POST['cid'];
	$course = course_load($cid);
	if ($course['Course_For_Guest'] == 0) {
		turn_on_course_guest($cid);
	} elseif ($course['Course_For_Guest'] == 1) {
		turn_off_course_guest($cid);
	}
	header('Location: '.$_SERVER['HTTP_REFERER'].'');
}
?>