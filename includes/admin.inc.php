<?php
/* Auth */
global $db;
session_name('okms');
if (!session_id()) session_start();
if (!isset($_SESSION['username'])) 
{
//If not isset -> set with dumy value 
$_SESSION['username'] = null;
}
$err = array();
if(isset($_POST['header_login']) || isset($_POST['wrap_login'])){
	// Will hold our errors
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	if(!count($err))
	{
		$_POST['username'] = mysqli_real_escape_string($db->link, $_POST['username']);
		$_POST['password'] = mysqli_real_escape_string($db->link, $_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];
		// Escaping all input data
		$row = mysqli_fetch_assoc(mysqli_query($db->link, "SELECT User_ID,User_Username,Role_ID,User_Status,User_Alias FROM ".PREFIX."USER WHERE (User_Username='{$_POST['username']}' OR User_Alias='{$_POST['username']}') AND User_Password='".md5($_POST['password'])."'"));
		if((isset($row['User_Username']) || isset($row['User_Alias'])) && $row['User_Status'] == 1)
		{
			// If everything is OK login
			$_SESSION['rid'] = $row['Role_ID'];
			$_SESSION['username'] = $row['User_Username'];
			$_SESSION['uid'] = $row['User_ID'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			// Store some data in the session
			setcookie('okmsRemember',$_POST['rememberMe']);
			if (isset($_POST['header_login'])) {
				header('Location: ./');
			} elseif (isset($_POST['wrap_login'])) {
				header('Location: '.$_SERVER['HTTP_REFERER']."");
			}
		}
		elseif(isset($row['User_Username']) && $row['User_Status'] == 0)
		{
			$err[]='User not confirmed email yet';
		}
		elseif(!isset($row['User_Username']))
		{
			$err[]='Wrong username and/or password!';
		}
	}
}
if (!username_existed($_SESSION['username'])) {
	// session_name('okms');
	session_unset();
	session_destroy();
} elseif (username_existed($_SESSION['username'])) {
	session_name('okms');
	session_start();
}
/* End Auth */
$p = isset($_GET['p']) ? rawurldecode($_GET['p']): 'home';

$pid = get_pid_from_url($p);
$uid = substr($p,5);
$username = get_username_from_url($p);
$week = get_week_from_url($p);
$course_week = get_course_week_from_url($p);
$cid = (isset($course_week)) ? $course_week['cid']: ((isset($_GET['cid'])) ? $_GET['cid']: get_cid_from_url($p));
$cids = ($_SESSION['rid'] == 1) ? cids_load_all(): user_cids_load_all($_SESSION['uid']);
$pids = user_pids_load_all($_SESSION['uid']);
$user = user_load($_SESSION['uid']);
$post = post_load($pid);
$course = course_load($cid);
$users = $db->array_load_all('USER');
$user_paths = array();
for ($i = 0; $i < count($users); $i++):
	$user_paths[$i] = 'user/'.$users[$i]['User_ID'];
endfor;

$profile_paths = array();
for ($i = 0; $i < count($users); $i++) {
	$profile_paths[$i] = 'user/'.$users[$i]['User_Username'];
}

$profile_follow_paths = array();
for ($i = 0; $i < count($users); $i++) {
	$profile_follow_paths[$i] = 'user/'.$users[$i]['User_Username'].'/follows';
}

$posts = $db->array_load_all('POST');
$post_paths = array();
for ($i = 0; $i < count($posts); $i++):
	$post_paths[$i] = 'question/'.$posts[$i]['Post_URL'];
endfor;

$courses = $db->array_load_all('COURSE');
$course_paths = array();
for ($i = 0; $i < count($courses); $i++):
	$course_paths[$i] = 'course/'.$courses[$i]['Course_Code'];
endfor;

$weeks = array('1','2','3','4','5','6','7','8','9','10','11','12');
$week_paths = array();
for ($i = 0; $i < count($weeks); $i++):
	$week_paths[$i] = 'week/'.$weeks[$i];
endfor;

$course_week_paths = array();
for ($i = 0; $i < count($courses); $i++) {
	for ($j = 0; $j < count($weeks); $j++) {
		$course_week_paths[] = 'course/'.$courses[$i]['Course_Code'].'/week/'.$weeks[$j];
	}
}
/* Title */
if (!isset($_SESSION['rid']) && isset($_GET['p']) && ($_GET['p'] == 'user/create' || $_GET['p'] == 'user/register')):
	$title = 'Register new account';
elseif (!isset($_SESSION['rid']) && isset($_GET['p']) && $_GET['p'] == 'user/password_reset'):
	$title = 'Lost your password?';
elseif (!isset($_SESSION['rid']) && isset($_GET['p']) && $_GET['p'] == 'user/verify'):
	$title = 'User verification';
elseif (!isset($_GET['p']) || $_GET['p'] == 'home'):
	$title = "";
	$body_class = 'front';
elseif ($p == 'search'):
	$title = (isset($_GET['q']) && $_GET['q'] != "") ? 'Search results for "'.$_GET['q'].'"'.(($cid != 0) ? ' in '.$course['Course_Code']: ""): 'Search results';
	$body_class = 'search';
elseif ($p == 'option'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1):
		$title = 'Options';
		$body_class = 'admin option';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || $_SESSION['rid'] == 3):
		$title = 'List courses';
		$body_class = 'admin course list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course/create'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Create course';
		$body_class = 'admin course';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course/edit'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || ($_SESSION['rid'] == 3 && $course['User_ID'] == $_SESSION['uid'])):
		$title = 'Edit course';
		$body_class = 'admin course';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course/delete'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Delete course';
		$body_class = 'admin course';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course/assign'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || ($_SESSION['rid'] == 3 && $course['User_ID'] == $_SESSION['uid'])):
		$title = 'Assign lecturers into course';
		$body_class = 'admin course';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course/enrol'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 1):
		$title = 'Enrol students into course';
		$body_class = 'admin course';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course/promote'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Promote course coordinator';
		$body_class = 'admin course';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course/csv'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] == 3):
		$title = 'Enrol students into course';
		$body_class = 'admin course';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'course/excel'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] == 3):
		$title = 'Enrol students into course';
		$body_class = 'admin course';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'menu'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'List menus';
		$body_class = 'admin menu list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'menu/create'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Create menu';
		$body_class = 'admin menu';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'menu/edit'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Edit menu';
		$body_class = 'admin menu';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'menu/delete'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Delete menu';
		$body_class = 'admin menu';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'post'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
		$title = 'List posts';
		$body_class = 'admin post list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'post/create'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
		$title = 'Create post';
		$body_class = 'admin post';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'post/edit'):
	if (isset($_SESSION['rid'])):
		$title = 'Edit post';
		$body_class = 'admin post';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'post/delete'):
	if (isset($_SESSION['rid'])):
		$title = 'Delete post';
		$body_class = 'admin post';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'post/archive'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
		$title = 'Knowledge base';
		$body_class = 'admin archive list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'report'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
		$title = 'Report';
		$body_class = 'admin report list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'role'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'List roles';
		$body_class = 'admin role list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'role/create'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Create role';
		$body_class = 'admin role';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'role/edit'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Edit role';
		$body_class = 'admin role';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'role/delete'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Delete role';
		$body_class = 'admin role';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'semester'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'List semesters';
		$body_class = 'admin semester list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'semester/create'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Create semester';
		$body_class = 'admin semester';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'semester/edit'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Edit semester';
		$body_class = 'admin semester';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'semester/delete'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Delete semester';
		$body_class = 'admin semester';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'type'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'List post types';
		$body_class = 'admin type list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'type/create'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Create post type';
		$body_class = 'admin type';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'type/edit'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Edit post type';
		$body_class = 'admin type';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'type/delete'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Delete post type';
		$body_class = 'admin type';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'user'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'List users';
		$body_class = 'admin user list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'user/create'):
	if (!isset($_SESSION['rid']) || isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'Create user';
		$body_class = 'admin user';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'user/edit'):
	if (isset($_SESSION['rid'])):
		$title = 'Edit user';
		$body_class = 'admin user';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'user/delete'):
	if (isset($_SESSION['rid'])):
		$title = 'Delete user';
		$body_class = 'admin user';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif (isset($_SESSION['rid']) && $p == 'user/password_reset'):
	$title = 'Not authorized';
	$body_class = 'not-authorized';
elseif (isset($_SESSION['rid']) && $p == 'user/verify'):
	$title = 'Not authorized';
	$body_class = 'not-authorized';
elseif ($p == 'user/csv'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'User CSV Importer';
		$body_class = 'admin user';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif ($p == 'user/excel'):
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
		$title = 'User Excel Importer';
		$body_class = 'admin user';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif (in_array($p,$user_paths)):
	$user = user_load($uid);
	if (isset($_SESSION['username']) && $_SESSION['username'] == $user['User_Username'] || $_SESSION['rid'] == 1):
		$title = 'Account of '.$user['User_Username'];
		$body_class = 'admin user list';
	else:
		$title = 'Not authorized';
		$body_class = 'not-authorized';
	endif;
elseif (in_array($p,$profile_paths)):
	$user = user_load_from_name($username);
	$title = 'Profile of '.((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username']);
	$body_class = 'profile';
elseif (in_array($p,$profile_follow_paths)):
	$user = user_load_from_name($username);
	$title = 'Following posts of '.((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username']);
	$body_class = 'profile';
elseif (in_array($p,$post_paths)):
	$post = post_load($pid);
	$title = $post['Post_Title'];
	$body_class = 'post';
elseif (in_array($p,$week_paths)):
	$title = 'Week '.$week;
	$body_class = 'week week-'.$week;
elseif (in_array($p,$course_paths)):
	$course = course_load($cid);
	$title = $course['Course_Code'].': '.$course['Course_Name'].((isset($_SESSION['uid']) && $course['User_ID'] == $_SESSION['uid']) ? ' - You are coordinator': "");
	$body_class = 'course';
elseif (in_array($p,$course_week_paths)):
	$cid = $course_week['cid'];
	$course = course_load($cid);
	$title = $course['Course_Code'].': '.$course['Course_Name'].((isset($_SESSION['uid']) && $course['User_ID'] == $_SESSION['uid']) ? ' - You are coordinator': "").' - Week '.$course_week['week'];
	$body_class = 'course week';
elseif ($p == 'sitemap'):
	$title = 'Sitemap';
	$body_class = 'sitemap';
elseif ($p == 'terms-and-conditions'):
	$title = 'Terms and Conditions';
	$body_class = 'terms';
else:
	$title = '404 page not found';
	$body_class = 'not-found';
endif;
if (in_array($p,$course_paths)):
	$course = course_load($cid);
	$body_class .= ' cid-'.$course['Course_ID'];
endif;
if (in_array($p,$course_week_paths)):
	$cid = $course_week['cid'];
	$course = course_load($cid);
	$body_class .= ' cid-'.$cid.' week-'.$course_week['week'];
endif;
if (isset($_SESSION['rid'])):
	$site_name = '[Logged in] Online KMS';
	$body_class .= ' logged-in';
else:
	$site_name = 'Online KMS';
endif;
$meta_description = 'Online Knowledge Management System'.((isset($post)) ? ' - '.$post['Post_Title'].': '.$post['Post_Question']: ((isset($course)) ? ' - '.$course['Course_Code'].': '.$course['Course_Name']: ((isset($title) && $title != '') ? ' - '.$title: '')));