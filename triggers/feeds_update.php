<?php //Paging for course pages
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$rid = (isset($_SESSION['rid'])) ? $_SESSION['rid']: 0;
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
$page = isset($_POST['page']) ? $_POST['page']: 1;
$cid = isset($_POST['cid']) ? $_POST['cid']: 0;
$week = isset($_POST['week']) ? $_POST['week']: 0;
$course = course_load($cid);
$user = user_load_from_name($username);
$profile_uid = $user['User_ID'];

$feeds_type = (isset($_POST['feeds_type'])) ? $_POST['feeds_type']: '';
switch ($feeds_type) {
	case 'front':
		echo front_page_listing(10,$uid,'sort_post_date_descend','All courses',$page);
		break;
	case 'course':
		echo view_course($cid,$uid,10,$page);
		break;
	case 'course_week':
		echo view_course_week($cid,$week,10,$uid,'sort_post_date_descend',$page);
		break;
	case 'post':
		echo view_post($pid,$uid,1);
		break;
	case 'profile':
		echo view_profile(10,$profile_uid,$uid,'sort_post_date_descend',$page);
		break;
	case 'profile_follow':
		echo view_profile_follow(10,$profile_uid,$uid,'sort_post_date_descend',$page);
		break;
	case 'week':
		echo view_week($week,10,$uid,'sort_post_date_descend',$page);
		break;
}
?>