<?php //Paging for course pages
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$rid = (isset($_SESSION['rid'])) ? $_SESSION['rid']: 0;
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
$course = course_load($cid);
$user = user_load_from_name($username);
$profile_uid = $user['User_ID'];

$feeds_type = (isset($_POST['feeds_type'])) ? $_POST['feeds_type']: '';
switch ($feeds_type) {
	case 'front':
		print front_page_listing(10,$uid,'sort_post_date_descend','All courses');
		break;
	case 'course':
		print view_course($cid,$uid,10);
		break;
	case 'course_week':
		print view_course_week($course_week['cid'],$course_week['week'],10,$uid,'sort_post_date_descend');
		break;
	case 'post':
		print view_post($pid,$uid,1);
		break;
	case 'profile':
		print view_profile(10,$profile_uid,$uid,'sort_post_date_descend');
		break;
	case 'profile_follow':
		print view_profile_follow(10,$profile_uid,$uid,'sort_post_date_descend');
		break;
	case 'week':
		print view_week($week,10,$uid,'sort_post_date_descend');
		break;
}
?>