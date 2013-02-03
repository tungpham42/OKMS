<?php //Allow or disallow course post
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['cid'])) {
	$cid = $_POST['cid'];
	$course = course_load($cid);
	if ($course['Course_Allowed'] == 0) {
		allow_course_post($cid);
	} elseif ($course['Course_Allowed'] == 1) {
		disallow_course_post($cid);
	}
	header('Location: '.$_SERVER['HTTP_REFERER'].'');
}
?>