<?php

require('database.inc.php');
require('libraries/class.phpmailer.php');
// Include the pagination class
require('libraries/pagination.class.php');
//PHPExcel Class
require('libraries/PHPExcel/PHPExcel.php');
require('libraries/PHPExcel/PHPExcel/IOFactory.php');
define('PREFIX', 'OKMS_');
define('DEFAULT_AVATAR', '/images/avatar.jpg');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/* General Functions */
global $db;
function convert_link($text) {
	$text = preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1">$1</a>', $text);
    $text = preg_replace('/([A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4})/', '<a href="mailto:$1">$1</a>', $text);
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
function table_row_class($id) { //Identify the table row class based on counter
	$output = "";
	if ((($id+1) % 2) == 1) {
		$output .= ' odd';
	} else {
		$output .= ' even';
	}
	return $output;
}
function substr_word($str,$start,$end) { //Substract words from content
	$end_pos = strpos($str,' ',$end);
	if ($pos !== false) {
		return substr($str,$start,$end_pos);
	}
}
function load_name_from_rid($rid) { //Load role name from role ID
	global $db;
	$roles = $db->array_load('ROLE','Role_ID',$rid);
	sort($roles);
	return $roles[0]['Role_Name'];
}
function post_load($pid) { //Load post array from post ID
	global $db;
	$posts = $db->array_load('POST','Post_ID',$pid);
	sort($posts);
	return isset($posts[0]) ? $posts[0]: null;
}
function posts_load_from_cid($cid) { //Load posts array from course ID
	global $db;
	$posts = $db->array_load('POST','Course_ID',$cid);
	sort($posts);
	return $posts;
}
function latest_post_load() { //Load latest post array
	global $db;
	$posts = $db->array_load_all('POST');
	usort($posts,'sort_post_date_descend');
	return isset($posts[0]) ? $posts[0]: null;
}
function latest_pid_load() { //Load latest pid
	$post = latest_post_load();
	return $post['Post_ID'];
}
function user_load($uid) { //Load user array from user ID
	global $db;
	$users = $db->array_load('USER','User_ID',$uid);
	sort($users);
	return isset($users[0]) ? $users[0] : null;
}
function user_load_from_name($name) { //Load user array from username
	global $db;
	$users = $db->array_load('USER','User_Username',$name);
	sort($users);
	return isset($users[0]) ? $users[0] : null;
}
function user_load_from_mail($mail) { //Load user array from email
	global $db;
	$users = $db->array_load('USER','User_Mail',$mail);
	sort($users);
	return isset($users[0]) ? $users[0] : null;
}
function followees_load_by_uid($uid) { //Load followees array from user ID
	global $db;
	$followees = $db->array_load('USER_FOLLOW','User_ID',$uid);
	sort($followees);
	return $followees;
}
function followee_ids_load_by_uid($uid) { //Load followee IDs array from user ID
	$followee_ids = array();
	$followees = followees_load_by_uid($uid);
	sort($followees);
	foreach ($followees as $followee) {
		$followee_ids[] = $followee['Followee_ID'];
	}
	sort($followee_ids);
	return $followee_ids;
}
function user_follow_load($uid,$followee_id) { //Load followee array from user ID and followee ID
	global $db;
	$followees = $db->array_load_with_two_identifier('USER_FOLLOW','User_ID',$uid,'Followee_ID',$followee_id);
	sort($followees);
	return isset($followees[0]) ? $followees[0] : null;
}
function role_load($rid) { //Load role array from role ID
	global $db;
	$roles = $db->array_load('ROLE','Role_ID',$rid);
	sort($roles);
	return isset($roles[0]) ? $roles[0] : null;
}
function semester_load($semid) { //Load semester array from semester ID
	global $db;
	$semesters = $db->array_load('SEMESTER','Semester_ID',$semid);
	sort($semesters);
	return isset($semesters[0]) ? $semesters[0] : null;
}
function course_load($cid) { //Load course array from course ID
	global $db;
	$courses = $db->array_load('COURSE','Course_ID',$cid);
	sort($courses);
	return isset($courses[0]) ? $courses[0] : null;
}
function course_load_from_code($code) { //Load course array from course code
	global $db;
	$courses = $db->array_load('COURSE','Course_Code',$code);
	sort($courses);
	return isset($courses[0]) ? $courses[0] : null;
}
function course_users_load($uid) { //Load array from table course_users with user ID
	global $db;
	$course_users = $db->array_load('USER_COURSE','User_ID',$uid);
	sort($course_users);
	return $course_users;
}
function cids_load_all() { //Load all course IDs into an array
	global $db;
	$courses = $db->array_load_all('COURSE');
	$cids = array();
	foreach ($courses as $course) {
		$cids[] = $course['Course_ID'];
	}
	return $cids;
}
function uids_load_all() { //Load all user IDs into an array
	global $db;
	$users = $db->array_load_all('USER');
	$uids = array();
	foreach ($users as $user) {
		$uids[] = $user['User_ID'];
	}
	return $uids;
}
function uids_load_all_by_rid($rid) { //Load all user IDs from role ID into an array
	global $db;
	$users = $db->array_load('USER','Role_ID',$rid);
	$uids = array();
	foreach ($users as $user) {
		$uids[] = $user['User_ID'];
	}
	return $uids;
}
function user_cids_load_all($uid) { //Load all course IDs from user ID into an array
	$course_users = course_users_load($uid);
	$cids = array();
	foreach ($course_users as $course_users) {
		$cids[] = $course_users['Course_ID'];
	}
	return $cids;
}
function user_pids_load_all($uid) { //Load all post IDs from user ID into an array
	$cids = user_cids_load_all($uid);
	$pids = array();
	foreach ($cids as $cid) {
		$posts = posts_load_from_cid($cid);
		foreach ($posts as $post) {
			$pids[] = $post['Post_ID'];
		}
	}
	return $pids;
}
function courses_load_from_uid($uid) { //Load courses array from user ID
	$course_users = course_users_load($uid);
	$courses = array();
	foreach ($course_users as $course_users) {
		$course = course_load($course_users['Course_ID']);
		$courses[] = $course;
	}
	return $courses;
}
function user_courses_load($cid) { //Load array from table course_users from course ID
	global $db;
	$user_courses = $db->array_load('USER_COURSE','Course_ID',$cid);
	sort($user_courses);
	return $user_courses;
}
function lecturers_load_from_cid($cid) { //Load lecturers array from course ID
	$users = users_load_by_cid($cid);
	$lecturers = array_filter($users, array(new Filter('3'), 'filter_rid'));
	return $lecturers;
}
function uids_load_from_cid($cid) { //Load user IDs array from course ID
	$user_courses = user_courses_load($cid);
	$uids = array();
	foreach ($user_courses as $user_course) {
		$uids[] = $user_course['User_ID'];
	}
	return $uids;
}
function users_load_by_cid($cid) { //Load users array from course ID
	$users = array();
	$user_courses = user_courses_load($cid);
	foreach ($user_courses as $user_course) {
		$user = user_load($user_course['User_ID']);
		$users[] = $user;
	}
	return $users;
}
function comment_load($comid) { //Load comment array from comment ID
	global $db;
	$comments = $db->array_load('COMMENT','Comment_ID',$comid);
	sort($comments);
	return isset($comments[0]) ? $comments[0]: null;
}
function comments_load_by_pid($pid) { //Load comments array from post ID
	global $db;
	$comments = $db->array_load('COMMENT','Post_ID',$pid);
	sort($comments);
	return $comments;
}
function post_vote_load($pid,$uid) { //Load post vote array from post ID and user ID
	global $db;
	$post_votes = $db->array_load_with_two_identifier('POST_VOTE','Post_ID',$pid,'User_ID',$uid);
	sort($post_votes);
	return isset($post_votes[0]) ? $post_votes[0]: null;
}
function post_votes_load_by_pid($pid) { //Load post votes array from post ID
	global $db;
	$post_votes = $db->array_load('POST_VOTE','Post_ID',$pid);
	sort($post_votes);
	return $post_votes;
}
function post_votes_load_by_uid($uid) { //Load post votes array from user ID
	global $db;
	$post_votes = $db->array_load('POST_VOTE','User_ID',$uid);
	sort($post_votes);
	return $post_votes;
}
function post_vote_pids_load_by_uid($uid) { //Load post vote pids array from user ID
	$pids = array();
	$post_votes = post_votes_load_by_uid($uid);
	sort($post_votes);
	foreach ($post_votes as $post_vote) {
		$pids[] = $post_vote['pid'];
	}
	sort($pids);
	return $pids;
}
function comment_vote_load($comid,$uid) { //Load comment vote array from comment ID and user ID
	global $db;
	$comment_votes = $db->array_load_with_two_identifier('COMMENT_VOTE','Comment_ID',$comid,'User_ID',$uid);
	sort($comment_votes);
	return isset($comment_votes[0]) ? $comment_votes[0]: null;
}
function post_follows_load_by_pid($pid) { //Load post follows array from post ID
	global $db;
	$post_follows = $db->array_load('POST_FOLLOW','Post_ID',$pid);
	sort($post_follows);
	return $post_follows;
}
function post_follows_load_by_uid($uid) { //Load post follows array from user ID
	global $db;
	$post_follows = $db->array_load('POST_FOLLOW','User_ID',$uid);
	sort($post_follows);
	return $post_follows;
}
function post_follow_pids_load_by_uid($uid) { //Load post follow pids array from user ID
	$pids = array();
	$post_follows = post_follows_load_by_uid($uid);
	sort($post_follows);
	foreach ($post_follows as $post_follow) {
		$pids[] = $post_follow['Post_ID'];
	}
	sort($pids);
	return $pids;
}
function post_follow_load($pid,$uid) { //Load post follow array from post ID and user ID
	global $db;
	$post_follows = $db->array_load_with_two_identifier('POST_FOLLOW','Post_ID',$pid,'User_ID',$uid);
	sort($post_follows);
	return isset($post_follows[0]) ? $post_follows[0]: null;
}
function post_rate_load($pid,$uid) { //Load post rate array from post ID and user ID
	global $db;
	$post_rates = $db->array_load_with_two_identifier('POST_RATE','Post_ID',$pid,'User_ID',$uid);
	sort($post_rates);
	return isset($post_rates[0]) ? $post_rates[0]: null;
}
function count_posts_from_cid($cid) { //Return total number of posts by course ID
	$posts = posts_load_from_cid($cid);
	return count($posts);
}
function count_post_likes_from_cid($cid) { //Return total number of posts by course ID
	$posts = posts_load_from_cid($cid);
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_likes($post['Post_ID']);
	}
	return $count;
}
function count_post_dislikes_from_cid($cid) { //Return total number of posts by course ID
	$posts = posts_load_from_cid($cid);
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_dislikes($post['Post_ID']);
	}
	return $count;
}
function count_post_follows_from_cid($cid) { //Return total number of posts by course ID
	$posts = posts_load_from_cid($cid);
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_follows($post['Post_ID']);
	}
	return $count;
}
function count_comments_from_cid($cid) { //Return total number of posts by course ID
	$posts = posts_load_from_cid($cid);
	$count = 0;
	foreach ($posts as $post) {
		$count += count_comments($post['Post_ID']);
	}
	return $count;
}
function count_post_likes_by_week($week) { //Return total number of likes by week
	global $db;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_likes($post['Post_ID']);
	}
	return $count;
}
function count_post_dislikes_by_week($week) { //Return total number of dislikes by week
	global $db;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_dislikes($post['Post_ID']);
	}
	return $count;
}
function count_post_follows_by_week($week) { //Return total number of follows by week
	global $db;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_follows($post['Post_ID']);
	}
	return $count;
}
function count_comments_by_week($week) { //Return total number of comments by week
	global $db;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	$count = 0;
	foreach ($posts as $post) {
		$count += count_comments($post['Post_ID']);
	}
	return $count;
}
function count_post_likes_by_course_week($cid,$week) { //Return total number of likes by cid and week
	$posts = posts_load_from_cid($cid);
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_likes($post['Post_ID']);
	}
	return $count;
}
function count_post_dislikes_by_course_week($cid,$week) { //Return total number of dislikes by cid and week
	$posts = posts_load_from_cid($cid);
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_dislikes($post['Post_ID']);
	}
	return $count;
}
function count_post_follows_by_course_week($cid,$week) { //Return total number of follows by cid and week
	$posts = posts_load_from_cid($cid);
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	$count = 0;
	foreach ($posts as $post) {
		$count += count_post_follows($post['Post_ID']);
	}
	return $count;
}
function count_comments_by_course_week($cid,$week) { //Return total number of comments by cid and week
	$posts = posts_load_from_cid($cid);
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	$count = 0;
	foreach ($posts as $post) {
		$count += count_comments($post['Post_ID']);
	}
	return $count;
}
function count_comments($pid) { //Return total number of comments by post ID
	$comments = comments_load_by_pid($pid);
	return count($comments);
}
function count_courses($uid) { //Return total number of courses by user ID
	$course_users = course_users_load($uid);
	return count($course_users);
}
function count_lecturers($cid) { //Return total number of lecturers by course ID
	$users = users_load_by_cid($cid);
	$lecturers = array_filter($users, array(new Filter('3'), 'filter_rid'));
	return count($lecturers);
}
function count_post_likes($pid) { //Return total number of post likes by post ID
	global $db;
	$post_votes = $db->array_load('POST_VOTE','Post_ID',$pid);
	$count = 0;
	foreach ($post_votes as $post_vote) {
		if ($post_vote['PostVote_Like'] == 1) {
			$count++;
		}
	}
	return $count;
}
function count_post_dislikes($pid) { //Return total number of post dislikes by post ID
	global $db;
	$post_votes = $db->array_load('POST_VOTE','Post_ID',$pid);
	$count = 0;
	foreach ($post_votes as $post_vote) {
		if ($post_vote['PostVote_Dislike'] == 1) {
			$count++;
		}
	}
	return $count;
}
function count_post_follows($pid) { //Return total number of post follows by post ID
	global $db;
	$post_follows = $db->array_load('POST_FOLLOW','Post_ID',$pid);
	return count($post_follows);
}
function count_comment_likes($comid) { //Return total number of comment likes by comment ID
	global $db;
	$comment_votes = $db->array_load('COMMENT_VOTE','Comment_ID',$comid);
	$count = 0;
	foreach ($comment_votes as $comment_vote) {
		if ($comment_vote['CommentVote_Like'] == 1) {
			$count++;
		}
	}
	return $count;
}
function count_comment_dislikes($comid) { //Return total number of comment dislikes by comment ID
	global $db;
	$comment_votes = $db->array_load('COMMENT_VOTE','Comment_ID',$comid);
	$count = 0;
	foreach ($comment_votes as $comment_vote) {
		if ($comment_vote['CommentVote_Dislike'] == 1) {
			$count++;
		}
	}
	return $count;
}
function total_post_rates($pid) { //Return total rating of post by post ID
	global $db;
	$post_rates = $db->array_load('POST_RATE','Post_ID',$pid);
	$total = 0;
	foreach ($post_rates as $post_rate) {
		$total += $post_rate['PostRate'];
	}
	return $total;
}
function count_post_rates($pid) { //Return total number of post rates by post ID
	global $db;
	$post_rates = $db->array_load('POST_RATE','Post_ID',$pid);
	return count($post_rates);
}
function count_post_rate($pid,$rate) { //Return total number of post rate by post ID
	global $db;
	$post_rates = $db->array_load('POST_RATE','Post_ID',$pid);
	$post_rates = array_filter($post_rates, array(new Filter($rate), 'filter_post_rate'));
	return count($post_rates);
}
function average_post_rates_with_decimal($pid,$decimal) { //Return total number of post rates by post ID
	$total = total_post_rates($pid);
	$count = count_post_rates($pid);
	return ($count != 0) ? number_format(($total/$count),$decimal): '0';
}
function average_post_rates($pid) {
	return average_post_rates_with_decimal($pid,0);
}
function count_posts_by_week($week) { //Return total number of posts by week
	global $db;
	$posts = $db->array_load('POST','Post_Week',$week);
	return count($posts);
}
function count_posts_by_course_week($cid,$week) { //Return total number of posts by week
	global $db;
	$posts = $db->array_load_with_two_identifier('POST','Course_ID',$cid,'Post_Week',$week);
	return count($posts);
}
function post_load_from_url_alias($url) { //Load post array from post type and post URL
	global $db;
	$posts = $db->array_load('POST','Post_URL',$url);
	sort($posts);
	return (isset($posts[0])) ? $posts[0]: null;
}
function get_pid_from_url($p) { //Get post ID from page URL, for use in admin.inc
	$pos = strpos($p,'/',0);
	$url = substr($p,$pos+1);
	$post = post_load_from_url_alias($url);
	return isset($post['Post_ID']) ? $post['Post_ID']: null;
}
function get_cid_from_url($p) { //Get course ID from page URL, for use in admin.inc
	$pos = strpos($p,'/',0);
	$code = substr($p,$pos+1);
	$pid = get_pid_from_url($p);
	$post = post_load($pid);
	$course = course_load_from_code($code);
	return (isset($post)) ? $post['Course_ID']: ((isset($course)) ? $course['Course_ID']: 0);
}
function get_username_from_url($p) { //Get username from page URL
	$start_pos = strpos($p,'/',0);
	$end_pos = (strpos($p,'/follows') !== false) ? strpos($p,'/follows',0): "";
	$username_length = (strpos($p,'/follows') !== false) ? $end_pos - $start_pos - 1: 0;
	$username = ($username_length != 0) ? substr($p,$start_pos+1,$username_length): substr($p,$start_pos+1);
	return $username;
}
function get_week_from_url($p) { //Get week number from page URL, for use in admin.inc
	$pos = strpos($p,'/',0);
	$week = substr($p,$pos+1);
	return $week;
}
function get_course_week_from_url($p) { //Get week number and course code from page URL, for use in admin.inc
	$course_start_pos = strpos($p,'/',0);
	$course_end_pos = strpos($p,'/week',0);
	$course_code_length = $course_end_pos - $course_start_pos - 1;
	$week_start_pos = $course_end_pos + 5;
	$course_code = substr($p,$course_start_pos+1,$course_code_length);
	$week = substr($p,$week_start_pos+1);
	$course = course_load_from_code($course_code);
	$cid = isset($course['Course_ID']) ? $course['Course_ID']: 0;
	$course_week = array(
					'cid' => $cid,
					'week' => $week
					);
	return (isset($course)) ? $course_week: null;
}
/* Auth Functions */
function error($msg) { //Show popup meesage
    echo '
    <script language="JavaScript">
    <!--
        openWrap("'.$msg.'");
    //-->
    </script>
    ';
}
//This functions checks and makes sure the email address that is being added to database is valid in format. 
function check_email_address($email) {
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}
function check_mail($str) //Check email format
{
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}
function send_mail($to,$subject,$body,$from) //Send mail with SMTP authentication
{
	$mail = new PHPMailer(true);
	$mail->IsSMTP();                                      	// Set mailer to use SMTP
	$mail->Host = $_ENV['MAIL_HOST'];                    	// Specify main and backup server
	$mail->Port = $_ENV['MAIL_PORT'];                       // Set the SMTP port
	$mail->CharSet = 'UTF-8';								// Set CharSet
	$mail->SMTPAuth = true;                               	// Enable SMTP authentication
	$mail->Username = $_ENV['MAIL_USERNAME'];               // SMTP username
	$mail->Password = $_ENV['MAIL_PASSWORD'];               // SMTP password
	$mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];           // Enable encryption, 'ssl' also accepted
//	$mail->SMTPDebug = 2;
	$mail->From = $from;
	$mail->FromName = 'OKMS';
	$mail->AddAddress($to);  // Add a recipient

	$mail->IsHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $body;

	if(!$mail->Send()) {
	   echo 'Message could not be sent.';
	   echo 'Mailer Error: ' . $mail->ErrorInfo;
	   exit;
	}

	echo 'Message has been sent';
}
function auth_error_array($name,$fullname,$pass,$mail,$rid,$pass1,$has_agreed) { //Return errors array from user name, password, email and role ID
	$err = array();
	$pos = strpos($mail,'@');
	$first_letter = substr($name,0,1);
	$premail = substr($mail,0,$pos);
	$domain = substr($mail,$pos+1);
	if(name_duplicated($name))
	{
		$err[]='Username already in use!';
	}
	if(strlen($name)<2 || strlen($name)>16)
	{
		$err[]='Your username must be between 3 and 16 characters!';
	}
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$name))
	{
		$err[]='Your username contains invalid characters!';
	}
	if(!check_mail($mail))
	{
		$err[]='Your email is not valid!';
	}
	if(mail_duplicated($mail))
	{
		$err[]='Email already in use!';
	}
	if($domain != 'rmit.edu.vn' && $domain != 'RMIT.EDU.VN' && $domain != 'student.rmit.edu.au' && $domain != 'STUDENT.RMIT.EDU.AU')
	{
		$err[]='Your email domain is invalid!';
	}
	if (($first_letter == 'v' || $first_letter == 'V' || $first_letter == 'e' || $first_letter == 'E') && preg_match('#[0-9]#',$premail))
	{ 
		$err[]='This is not a lecturer email!';
	}
	if (($first_letter == 's' || $first_letter == 'S' || $first_letter == 'l' || $first_letter == 'L') && $name != $premail)
	{ 
		$err[]='Student ID and Student email do not match!';
	}
	if(strlen($fullname)>16)
	{
		$err[]='Your full name must be smaller than 16 characters!';
	}
	if(strlen($pass)<5 || strlen($pass)>16)
	{
		$err[]='Your password must be between 6 and 16 characters!';
	}
	if($pass != $pass1)
	{
		$err[]='Password not match';
	}
	if($rid == "")
	{
		$err[]='Role not identified';
	}
	if($has_agreed == 0)
	{
		$err[]='You did not check the box agreeing to abide by the Terms and Conditions';
	}
	return $err;
}
function auth_guest_error_array($name,$fullname,$pass,$mail,$rid,$pass1,$has_agreed) { //Return errors array from user name, password, email and role ID
	$err = array();
	$pos = strpos($mail,'@');
	$first_letter = substr($name,0,1);
	$premail = substr($mail,0,$pos);
	$domain = substr($mail,$pos+1);
	if(name_duplicated($name))
	{
		$err[]='Username already in use!';
	}
	if(strlen($name)<2 || strlen($name)>16)
	{
		$err[]='Your username must be between 3 and 16 characters!';
	}
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$name))
	{
		$err[]='Your username contains invalid characters!';
	}
	if(!check_mail($mail))
	{
		$err[]='Your email is not valid!';
	}
	if(mail_duplicated($mail))
	{
		$err[]='Email already in use!';
	}
	if(strlen($fullname)>16)
	{
		$err[]='Your full name must be smaller than 16 characters!';
	}
	if(strlen($pass)<5 || strlen($pass)>16)
	{
		$err[]='Your password must be between 6 and 16 characters!';
	}
	if($pass != $pass1)
	{
		$err[]='Password not match';
	}
	if($has_agreed == 0)
	{
		$err[]='You did not check the box agreeing to abide by the Terms and Conditions';
	}
	return $err;
}
function mail_duplicated($mail) {
	global $db;
	$users = $db->array_load('USER','User_Mail',$mail);
	sort($users);
	if (count($users) > 0) {
		return true;
	} else {
		return false;
	}
}
function name_duplicated($name) {
	global $db;
	$users = $db->array_load('USER','User_Username',$name);
	sort($users);
	if (count($users) > 0) {
		return true;
	} else {
		return false;
	}
}
function pass_error($pass) { //Return error meesage from password
	$err = array();
	if(strlen($pass)<5 || strlen($pass)>32)
	{
		$err[]='Your password must be between 6 and 32 characters!';
	}
	return $err;
}
function pass_error_array($uid,$current_pass,$pass,$pass1) { //Return error meesage from password
	$err = array();
	$user = user_load($uid);
	$user_pass = $user['User_Password'];
	$current_pass = md5($current_pass);
	if(strlen($pass)<5 || strlen($pass)>32)
	{
		$err[]='Your password must be between 6 and 32 characters!';
	}
	if($user_pass != $current_pass)
	{
		$err[]='Current password not correct';
	}
	if($pass != $pass1)
	{
		$err[]='Password not match';
	}
	return $err;
}
/* User Functions */
function list_users($rid=0,$count=0,$page=1) { //Return users list, for admin use
	global $db;
	$output = "";
	$users = $db->array_load_all('USER');
	if ($rid != 0) {
		$users = array_filter($users, array(new Filter($rid), 'filter_rid'));
	}
	usort($users,'sort_user_ascend');
	$user_count = count($users);
	$pagination = new pagination($users,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$users = $pagination->getResults();
	//$output .= '<a class="button" href="/user/csv">User CSV Importer</a>';
	$output .= '<a class="button" href="/user/create">Create user</a>';
	$output .= '<span class="count" colspan="7">'.$user_count.' user'.(($user_count > 1) ? 's': "").' to display.</span>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<table>';
	$output .= '<tr><th>Username</th><th>Full name</th><th>Mail</th><th>Role</th><th>Created time</th><th colspan="3">Operations</th></tr>';
	for ($i = 0; $i < count($users); $i++) {
		if (isset($users[$i])){
			$role = role_load($users[$i]['Role_ID']);
			$class = 'class="'.table_row_class($i).'"';
			$course_users = course_users_load($users[$i]['User_ID']);
			$output .= '<tr '.$class.'>';
			$output .= '<td class="center"><a href="/user/'.$users[$i]['User_Username'].'">'.$users[$i]['User_Username'].'</a></td>';
			$output .= '<td>'.$users[$i]['User_Fullname'].'</td>';
			$output .= '<td>'.$users[$i]['User_Mail'].'</td>';
			$output .= '<td class="center">'.$role['Role_Name'].'</td>';
			$output .= '<td class="center">'.date('d-m-Y',$users[$i]['User_Created']).'</td>';
			$output .= '<td class="center"><form method="POST" action="/triggers/activate_user.php"><input type="hidden" name="uid" value="'.$users[$i]['User_ID'].'" /><input name="'.(($users[$i]['User_Status'] == 1) ? 'user_activate': 'user_deactivate').'" type="submit" title="'.(($users[$i]['User_Status'] == 0) ? 'Activate': 'Deactivate').' user" value="'.(($users[$i]['User_Status'] == 0) ? 'Activate': 'Deactivate').' user"/></form></td>';
			$output .= '<td class="center"><form method="POST" action="/user/edit"><input type="hidden" name="uid" value="'.$users[$i]['User_ID'].'" /><input type="hidden" name="old_name" value="'.$users[$i]['User_Username'].'" /><input type="hidden" name="old_fullname" value="'.$users[$i]['User_Fullname'].'" /><input type="hidden" name="old_mail" value="'.$users[$i]['User_Mail'].'" /><input type="hidden" name="old_rid" value="'.$users[$i]['Role_ID'].'" /><input name="user_edit" type="submit" title="Edit" value="Edit"/></form></td>';
			$output .= '<td class="center"><form method="POST" action="/user/delete"><input type="hidden" name="uid" value="'.$users[$i]['User_ID'].'" /><input name="user_delete" type="submit" title="Delete" value="Delete"/></form></td>';
			$output .= '</tr>';
		}
	}
	$output .= '</table>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#admin_user_section").load("/triggers/admin_user.php",{rid:'.$rid.',count:'.$count.',page:page});
				}
				</script>';
	return $output;
}
function view_profile($count,$profile_uid,$uid,$sort_type,$page=1) { //Return list of posts for each user
	global $db;
	$output = "";	
	$default = DEFAULT_AVATAR;
	$posts = $db->array_load('POST','User_ID',$profile_uid);
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	$posts = array_filter($posts, array(new Filter(0), 'filter_post_hide'));
	sort($posts);
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['comments_count'] = count_comments($posts[$p]['Post_ID']);
	}
	usort($posts,$sort_type);
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<div class="posts">';
	for ($i = 0; $i < count($posts); $i++) {
		if ($posts[$i]['Post_ID']) {
			$pid = $posts[$i]['Post_ID'];
			$output .= view_post($pid,$uid);
			$output .= ($i != (count($posts)-1)) ? '<hr>': "";
		}
	}
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#feeds").load("/triggers/paging_profile.php",{count:'.$count.',profile_uid:'.$profile_uid.',page:page});
				}
				</script>';
	if (count($posts) == 0) {
		$output .= '<h3>This user has no post yet.</h3>';
	}
	$output .= '</div>';
	return $output;
}
/* Followed post listing functions */
function view_profile_follow($count,$profile_uid,$uid,$sort_type,$page=1) { //Return list of followed posts
	$output = "";	
	$default = DEFAULT_AVATAR;
	$posts = array();
	$pids = post_follow_pids_load_by_uid($profile_uid);
	sort($pids);
	for ($p = 0; $p < count($pids); $p++) {
		$posts[$p] = post_load($pids[$p]);
	}
	sort($posts);
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	sort($posts);
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['comments_count'] = count_comments($posts[$p]['Post_ID']);
	}
	sort($posts);
	usort($posts,$sort_type);
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<div class="posts">';
	for ($i = 0; $i < count($posts); $i++) {
		if ($posts[$i]['Post_ID']) {
			$pid = $posts[$i]['Post_ID'];
			$output .= view_post($pid,$uid);
			$output .= ($i != (count($posts)-1)) ? '<hr>': "";
		}
	}
	$output .= '</div>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#feeds").load("/triggers/paging_profile_follow.php",{count:'.$count.',profile_uid:'.$profile_uid.',page:page});
				}
				</script>';
	if (count($posts) == 0) {
		$output .= '<h3>This user has not followed any post.</h3>';
	}
	return $output;
}
function view_user($uid) { //Return user account, for every authenticated user's use
	$output = "";
	$user = user_load($uid);
	$course_users = course_users_load($uid);
	$output .= '<table>';
	$output .= '<tr><th>User ID</th><th>Username</th><th>Mail</th><th>Role</th><th>Courses</th><th colspan="2">Operations</th></tr>';
	$output .= '<tr>';
	$output .= '<td>'.$user['User_ID'].'</td>';
	$output .= '<td>'.$user['User_Username'].'</td>';
	$output .= '<td>'.$user['User_Mail'].'</td>';
	$output .= '<td>'.load_name_from_rid($user['Role_ID']).'</td>';
	$output .= '<td>'.view_user_courses($user['User_ID']).'</td>';
	$output .= '<td class="center"><form method="POST" action="/user/edit"><input type="hidden" name="uid" value="'.$user['User_ID'].'" /><input type="hidden" name="old_name" value="'.$user['User_Username'].'" /><input type="hidden" name="old_fullname" value="'.$user['User_Fullname'].'" /><input type="hidden" name="old_mail" value="'.$user['User_Mail'].'" /><input type="hidden" name="old_rid" value="'.$user['Role_ID'].'" /><input name="user_edit" type="submit" value="Edit"/></form></td>';
	$output .= ($_SESSION['rid'] != 2 && $_SESSION['rid'] != 3) ? '<td class="center"><form method="POST" action="/user/delete"><input type="hidden" name="uid" value="'.$user['User_ID'].'" /><input name="user_delete" type="submit" value="Delete"/></form></td>': '<td class="empty"></td>';
	$output .= '</tr>';
	$output .= '</table>';
	return $output;
}
function create_user($rid,$name,$fullname,$alias,$pass,$mail,$hash) { //Create new user
	global $db;
	$pass = md5($pass);
	$array = array(
				'Role_ID' => $rid,
				'User_Username' => $name,
				'User_Fullname' => $fullname,
				'User_Alias' => $alias,
				'User_Password' => $pass,
				'User_Mail' => $mail,
				'User_Created' => time(),
				'User_Hash' => $hash,
				'User_Status' => "0"
			);
	$db->insert_record($array,'USER');
}
function edit_user($uid,$rid,$fullname,$pass) { //Edit user details including role ID, fullname, password
	global $db;
	$pass = md5($pass);
	$array = array(
				'Role_ID' => $rid,
				'User_Fullname' => $fullname,
				'User_Password' => $pass
			);
	$db->update_record($array,'User_ID',$uid,'USER');
}
function edit_user_without_pass($uid,$rid,$fullname) { //Edit user details including role ID, fullname
	global $db;
	$array = array(
				'Role_ID' => $rid,
				'User_Fullname' => $fullname
			);
	$db->update_record($array,'User_ID',$uid,'USER');
}
function delete_user($uid) { //Delete user with user ID
	global $db;
	$db->delete_record('User_ID',$uid,'USER');
	delete_all_course_user($uid);
}
function promote_user($coor_uid,$cid) { //Promote lecturer to course coordinator
	global $db;
	$array = array(
				'User_ID' => $coor_uid
			);
	$db->update_record($array,'Course_ID',$cid,'COURSE');
}
function activate_user($uid) { //Activate user, change user status to 1
	global $db;
	$array = array(
				'User_Status' => '1'
			);
	$db->update_record($array,'User_ID',$uid,'USER');
}
function deactivate_user($uid) { //Deactivate user, change user status to 0
	global $db;
	$array = array(
				'User_Status' => '0'
			);
	$db->update_record($array,'User_ID',$uid,'USER');
}
function select_coor_uid($name,$cid) { //Return select element of lecturer user IDs
	$output = "";
	$course = course_load($cid);
	$users = users_load_by_cid($cid);
	$lecturers = array_filter($users, array(new Filter('3'), 'filter_rid'));
	sort($lecturers);
	$output .= '<select name="'.$name.'">';
	$output .= '<option value="0">None</option>';
	for ($i = 0; $i < count($lecturers); $i++) {
		$selected = ($lecturers[$i]['User_ID'] == $course['User_ID']) ? 'selected': "";
		$output .= '<option '.$selected.' value="'.$lecturers[$i]['User_ID'].'">'.((isset($lecturers[$i]['User_Fullname'])) ? $lecturers[$i]['User_Fullname'].' - '.$lecturers[$i]['User_Username']: $lecturers[$i]['User_Username']).'</option>';
	}
	$output .= '</select>';
	return $output;
}
function csv_update_pass($name,$pass) { //Batch update user password
	global $db;
	$array = array(
				'User_Password' => $pass
			);
	$db->update_record($array,'User_Username',$name,'USER');
}
function csv_update_email($name,$mail) { //Batch update user email
	global $db;
	$array = array(
				'User_Mail' => $mail
			);
	$db->update_record($array,'User_Username',$name,'USER');
}
function csv_create_user($name,$pass,$mail) { //Batch create user
	create_user('2',$name,$pass,$mail);
}
function user_existed($uid) { //Check if user existed
	$user = user_load($uid);
	if (isset($user)) {
		return true;
	} else {
		return false;
	}
}
function username_existed($name) { //Check if username existed
	$user = user_load_from_name($name);
	if (isset($user)) {
		return true;
	} else {
		return false;
	}
}
/* User Follow Functions */
function following_box($followee_id) { //Friend box
	$output = "";
	$output .= '<div class="following_box">';
	$output .= ($followee_id != $_SESSION['uid']) ? '<a id="user_followee_id_'.$followee_id.'" class="button'.((is_followee($_SESSION['uid'],$followee_id)) ? ' user_unfollow': ' user_follow').'">'.((is_followee($_SESSION['uid'],$followee_id)) ? 'Followed': 'Follow').'</a>': "";
	$output .= '<div id="save_user_follow_id_'.$followee_id.'"></div>';
	$output .= '<script>
				$(".following_box > .button.user_unfollow").mouseenter(function(){
					$(this).text("Unfollow");
				}).mouseleave(function(){
					$(this).text("Followed");
				});
				$("#user_followee_id_'.$followee_id.'").click(function(){
					$("#save_user_follow_id_'.$followee_id.'").load("/triggers/user_follow.php",{followee_id:'.$followee_id.'},function(){
						location.reload();
					});
				});
				</script>';
	$output .= '</div>';
	return $output;
}
function following_list($uid) { //Friend list
	$output = "";
	$default = DEFAULT_AVATAR;
	$size = 40;
	$followee_ids = followee_ids_load_by_uid($uid);
	sort($followee_ids);
	$output .= '<div class="heading">Following</div>';
	$output .= '<div id="following_list">';
	if (count($followee_ids) > 0) {
		for ($i = 0; $i < count($followee_ids); $i++) {
			$user = user_load($followee_ids[$i]);
			$email = $user['User_Mail'];
			$grav_url = "https://0.gravatar.com/avatar/" . md5( strtolower( trim((string) $email ) ) ) . "?d=identicon&s=" . $size;
			$output .= '<div class="post '.(($i == 0) ? 'first': "").'">';
			$output .= '<a class="author" href="/user/'.$user['User_Username'].'"><img alt="'.$user['User_Username'].'" src="'.$grav_url.'" width="40px" height="40px"/></a>';
			$output .= '<div class="post_right_detail">';
			$output .= '<span class="author_name"><a href="/user/'.$user['User_Username'].'">'.$user['User_Fullname'].'</a></span><br/>';
			$output .= '</div>';
			$output .= '</div>';
		}
	} else {
		$output .= '<div>No followees yet</div>';
	}
	$output .= '</div>';
	return $output;
}
function is_followee($uid,$followee_id) {
	$followee = user_follow_load($uid,$followee_id);
	if (isset($followee)) {
		return true;
	} else {
		return false;
	}
}
function follow_user($uid,$followee_id) { //Create new record in table course_users
	global $db;
	$array = array(
				'User_ID' => $uid,
				'Followee_ID' => $followee_id
			);
	$db->insert_record($array,'USER_FOLLOW');
}
function unfollow_user($uid,$followee_id) { //Delete record in table course_users
	global $db;
	$db->delete_record_with_two_identifier('User_ID',$uid,'Followee_ID',$followee_id,'USER_FOLLOW');
}
function delete_all_followees($uid) { //Delete all records in table course_users by user ID
	global $db;
	$db->delete_record('User_ID',$uid,'USER_FOLLOW');
}
/* Role Functions */
function select_role($name,$rid = null) { //Return select element of role IDs
	global $db;
	$output = "";
	if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1){
		$roles = $db->array_load_all('ROLE');
	}elseif (!isset($_SESSION['rid']) || $_SESSION['rid'] != 1){
		$roles = $db->array_load_with_two_values('ROLE','Role_ID','2','3');
	}
	$output .= '<select id="'.$name.'" name="'.$name.'">';
	for ($i = 0; $i < count($roles); $i++) {
		$selected = ($rid != null && $rid == $roles[$i]['Role_ID']) ? 'selected': "";
		$output .= '<option '.$selected.' value="'.$roles[$i]['Role_ID'].'">'.$roles[$i]['Role_Name'].'</option>';
	}
	$output .= '</select>';
	return $output;
}
function radio_role($name,$rid = null) { //Return radio button element of role IDs
	global $db;
	$output = "";
	if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1){
		$roles = $db->array_load_all('ROLE');
	}elseif (!isset($_SESSION['rid']) || $_SESSION['rid'] != 1){
		$roles = $db->array_load_with_two_values('ROLE','Role_ID','2','3');
	}
	for ($i = 0; $i < count($roles); $i++) {
		$checked = ($rid != null && $rid == $roles[$i]['Role_ID']) ? 'checked': "";
		$output .= '<input type="radio" '.$checked.' name="'.$name.'" value="'.$roles[$i]['Role_ID'].'"/>'.$roles[$i]['Role_Name'].'<br/>';
	}
	return $output;
}
function list_roles() { //Return list of roles, for admin use
	global $db;
	$output = "";
	$roles = $db->array_load_all('ROLE');
	$output .= '<a class="button" href="/role/create">Create role</a>';
	$output .= '<span class="count" colspan="4">'.count($roles).' role'.((count($roles) > 1) ? 's': "").' to display.</span>';
	$output .= '<table>';
	$output .= '<tr><th>Role ID</th><th>Role name</th><th colspan="2">Operations</th></tr>';
	for ($i = 0; $i < count($roles); $i++) {
		$class = 'class="'.table_row_class($i).'"';
		$output .= '<tr '.$class.'>';
		$output .= '<td class="center">'.$roles[$i]['Role_ID'].'</td>';
		$output .= '<td class="center">'.$roles[$i]['Role_Name'].'</td>';
		$output .= '<td class="center"><form method="POST" action="/role/edit"><input type="hidden" name="rid" value="'.$roles[$i]['Role_ID'].'" /><input type="hidden" name="name" value="'.$roles[$i]['Role_Name'].'" /><input name="role_edit" type="submit" title="Edit" value="Edit"/></form></td>';
		$output .= '<td class="center"><form method="POST" action="/role/delete"><input type="hidden" name="rid" value="'.$roles[$i]['Role_ID'].'" /><input name="role_delete" type="submit" title="Delete" value="Delete"/></form></td>';
		$output .= '</tr>';
	}
	$output .= '</table>';
	return $output;
}
function create_role($name) { //Create new role
	global $db;
	$array = array(
				'Role_Name' => $name
			);
	$db->insert_record($array,'ROLE');
}
function edit_role($rid,$name) { //Edit role detail including role name
	global $db;
	$array = array(
				'Role_Name' => $name
			);
	$db->update_record($array,'Role_ID',$rid,'ROLE');
}
function delete_role($rid) { //Delete role
	global $db;
	$db->delete_record('Role_ID',$rid,'ROLE');
}
/* Semester Functions */
function current_semester_load() { //Load array of current semester
	global $db;
	$semesters = $db->array_load('SEMESTER','Semester_Current','1');
	sort($semesters);
	return isset($semesters[0]) ? $semesters[0]: null;
}
function list_semesters() { //Return list of roles, for admin use
	global $db;
	$output = "";
	$current_semester = current_semester_load();
	$semesters = $db->array_load_all('SEMESTER');
	usort($semesters,'sort_semester_start_date_ascend');
	$output .= '<a class="button" href="/semester/create">Create semester</a>';
	$output .= '<span class="count" colspan="6">'.count($semesters).' semester'.((count($semesters) > 1) ? 's': "").' to display.</span>';
	$output .= '<table>';
	$output .= '<tr><th>Current</th><th>Semester Code</th><th>Semester start date</th><th>Semester end date</th><th colspan="2">Operations</th></tr>';
	for ($i = 0; $i < count($semesters); $i++) {
		$class = 'class="'.table_row_class($i).'"';
		$output .= '<tr '.$class.'>';
		$output .= '<td class="center"><input '.(($current_semester['Semester_ID'] == $semesters[$i]['Semester_ID']) ? 'checked': "").' type="radio" name="current_semester" value="'.$semesters[$i]['Semester_ID'].'" /></td>';
		$output .= '<td class="center">'.$semesters[$i]['Semester_Code'].'</td>';
		$output .= '<td class="center">'.date('Y-m-d',$semesters[$i]['Semester_Start_Date']).'</td>';
		$output .= '<td class="center">'.date('Y-m-d',$semesters[$i]['Semester_End_Date']).'</td>';
		$output .= '<td class="center"><form method="POST" action="/semester/edit"><input type="hidden" name="semid" value="'.$semesters[$i]['Semester_ID'].'" /><input type="hidden" name="old_semester_code" value="'.$semesters[$i]['Semester_Code'].'" /><input type="hidden" name="old_semester_start_date" value="'.$semesters[$i]['Semester_Start_Date'].'" /><input type="hidden" name="old_semester_end_date" value="'.$semesters[$i]['Semester_End_Date'].'" /><input name="semester_edit" type="submit" title="Edit" value="Edit"/></form></td>';
		$output .= '<td class="center"><form method="POST" action="/semester/delete"><input type="hidden" name="semid" value="'.$semesters[$i]['Semester_ID'].'" /><input name="semester_delete" type="submit" title="Delete" value="Delete"/></form></td>';
		$output .= '</tr>';
	}
	$output .= '<tr colspan="6"><td><form method="POST" action="/triggers/set_current_semester.php"><input type="hidden" name="current_semid" id="current_semester" /><input name="semester_set_current" type="submit" value="Set current"/></form></td></tr>';
	$output .= '</table>';
	$output .= '<script>
				$("input:radio[name=current_semester]").change(function(){
					$("input#current_semester").val($("input:radio[name=current_semester]:checked").val());
				});
				</script>';
	return $output;
}
function create_semester($semester_code,$semester_start_date,$semester_end_date) { //Create new semester
	global $db;
	$array = array(
				'Semester_Code' => $semester_code,
				'Semester_Start_Date' => $semester_start_date,
				'Semester_End_Date' => $semester_end_date
			);
	$db->insert_record($array,'SEMESTER');
}
function edit_semester($semid,$semester_code,$semester_start_date,$semester_end_date) { //Edit semester details
	global $db;
	$array = array(
				'Semester_Code' => $semester_code,
				'Semester_Start_Date' => $semester_start_date,
				'Semester_End_Date' => $semester_end_date
			);
	$db->update_record($array,'Semester_ID',$semid,'SEMESTER');
}
function delete_semester($semid) { //Delete semester
	global $db;
	$db->delete_record('Semester_ID',$semid,'SEMESTER');
}
function set_current_semester($semid) { //Set current semester
	global $db;
	$db->update_record(array('Semester_Current' => '1'),'Semester_ID',$semid,'SEMESTER');
	$db->update_record_with_operator(array('Semester_Current' => '0'),'Semester_ID',$semid,'SEMESTER','!=');
}
/* Post Functions */
function view_post($pid,$uid,$button=0) { //Return post from post ID
	$output = "";
	$post = post_load($pid);
	$user = user_load($post['User_ID']);
	$course = course_load($post['Course_ID']);
	$cid = $post['Course_ID'];
	$role = role_load($user['Role_ID']);
	$post_vote = post_vote_load($pid,$_SESSION['uid']);
	$post_follow = post_follow_load($pid,$_SESSION['uid']);
	$post_rate = post_rate_load($pid,$_SESSION['uid']);
	$title = $post['Post_Title'];
	$email = $user['User_Mail'];
	$size = 40;
	$default = DEFAULT_AVATAR;
	$i = 0;
	$grav_url = "https://0.gravatar.com/avatar/" . md5( strtolower( trim((string) $email ) ) ) . "?d=identicon&s=" . $size;
	$output .= '<div class="post '.(($i == 0) ? 'first': "").'">';
	$output .= '<a class="author" href="'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? '#': '/user/'.$user['User_Username']).'"><img alt="'.$user['User_Username'].'" src="'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? $default: $grav_url).'" width="40px" height="40px"/></a>';
	$output .= '<div class="post_right_detail">';
	$output .= '<span class="author_name'.(($user['Role_ID'] == 3) ? ' lecturer': "").'">'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? "": '<a href="/user/'.$user['User_Username'].'">').((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? 'Anonymous': ((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username'])).((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? "": '</a>').'</span><br/>';
	$output .= '<a class="post_title" href="/question/'.$post['Post_URL'].'">'.$post['Post_Title'].'</a><br/>';
	$output .= '<a class="course_code" href="/course/'.$course['Course_Code'].'">'.$course['Course_Code'].'</a><a href="/course/'.$course['Course_Code'].'/week/'.$post['Post_Week'].'"><span class="course_name">.'.$course['Course_Name'].'</span> > <span class="post_week">Week '.$post['Post_Week'].'</span></a><br/>';
	$output .= '<span class="post_content">'.htmlspecialchars_decode($post['Post_Question']).'</span><br/>';
	$output .= ($post['Post_Answer'] != "") ? '<div class="post_answer"><div class="post_answer_label">Answer:</div><div class="post_answer_content">'.htmlspecialchars_decode($post['Post_Answer']).'</div></div>': "";
	$output .= ($uid != 0 && $post_rate['User_ID'] != $uid && $_SESSION['rid'] != 1) ? star_rating($pid) : '<div title="Your rating" id="post_rate_pid_'.$pid.'" class="rate_widget">'.star_rating_update($pid).'</div><div title="Average rating: '.average_post_rates_with_decimal($pid,1).'" id="average_post_rate_pid_'.$pid.'" class="average_rate">'.star_rating_average($pid).'</div>';
	$output .= ($uid != 0) ? '<div id="save_post_rate_pid_'.$pid.'"></div>': "";
	$output .= ($uid != 0) ? '<a title="'.(($post_vote['PostVote_Like'] == 0) ? 'Like': 'Unlike').' this post" class="button'.(($post_vote['PostVote_Like'] == 0) ? ' like': ' like clicked').'" id="post_like_pid_'.$pid.'">'.count_post_likes($pid).' Like'.((count_post_likes($pid) == 0 || count_post_likes($pid) == 1) ? "": 's').'</a>': '<a title="Like this post" class="button like disabled" id="post_like_pid_'.$pid.'">'.count_post_likes($pid).' Like'.((count_post_likes($pid) == 0 || count_post_likes($pid) == 1) ? "": 's').'</a>';
	$output .= ($uid != 0) ? '<div id="save_post_like_pid_'.$pid.'"></div>': "";
	$output .= ($uid != 0) ? '<a title="'.(($post_vote['PostVote_Dislike'] == 0) ? 'Dislike': 'Undislike').' this post" class="button'.(($post_vote['PostVote_Dislike'] == 0) ? ' dislike': ' dislike clicked').'" id="post_dislike_pid_'.$pid.'">'.count_post_dislikes($pid).' Dislike'.((count_post_dislikes($pid) == 0 || count_post_dislikes($pid) == 1) ? "": 's').'</a>': '<a title="Dislike this post" class="button dislike disabled" id="post_dislike_pid_'.$pid.'">'.count_post_dislikes($pid).' Dislike'.((count_post_dislikes($pid) == 0 || count_post_dislikes($pid) == 1) ? "": 's').'</a>';
	$output .= ($uid != 0) ? '<div id="save_post_dislike_pid_'.$pid.'"></div>': "";
	$output .= ($uid != 0) ? '<a title="'.(($post_follow['User_ID'] != $uid) ? 'Follow': 'Unfollow').' this post" class="button'.(($post_follow['User_ID'] != $uid) ? ' follow': ' follow clicked').'" id="post_follow_pid_'.$pid.'">'.count_post_follows($pid).' Follow'.((count_post_follows($pid) == 0 || count_post_follows($pid) == 1) ? "": 's').'</a>': '<a title="Follow this post" class="button follow disabled" id="post_follow_pid_'.$pid.'">'.count_post_follows($pid).' Follow'.((count_post_follows($pid) == 0 || count_post_follows($pid) == 1) ? "": 's').'</a>';
	$output .= '<div id="save_post_follow_pid_'.$pid.'"></div>';
	$output .= '<a id="comments_count_pid_'.$pid.'" title="Leave a comment" class="button comment_toggle">'.count_comments($pid).' Comment'.((count_comments($pid) == 0 || count_comments($pid) == 1) ? "": 's').'</a>';
	$output .= '<span class="post_time">'.ago($post['Post_Created']).'</span>';
	$output .= ($uid != 0) ? list_comments($pid): list_comments_without_right($pid);
	$output .= ($uid != 0) ? 
				'<script>
				$("#post_like_pid_'.$pid.'").click(function(){
					postLike('.$pid.');
				});
				$("#post_dislike_pid_'.$pid.'").click(function(){
					postDislike('.$pid.');
				});
				$("#post_follow_pid_'.$pid.'").click(function(){
					postFollow('.$pid.');
				});
				</script>
				': "";
	$output .= '<script>
				if ($("#comments_pid_'.$pid.'").css("display") == "none") {
					$("#comments_count_pid_'.$pid.'").removeClass("clicked");
				} else if ($("#comments_pid_'.$pid.'").css("display") == "block") {
					$("#comments_count_pid_'.$pid.'").addClass("clicked");
				}
				$("#comments_count_pid_'.$pid.'").click(function(){
					$.ajax({
						type: "POST",
						url: "/triggers/validate_comments_count.php",
						data: {
							"pid": '.$pid.'
						},
						dataType: "text"
					}).done(function(data){
						if (data.includes("not_loggedin") && data.includes("no_comment")) {
							openLogin();
						} else {
							toggle_comments('."'".'comments_pid_'.$pid."'".',this);
						}
						if ($("#comments_pid_'.$pid.'").css("display") == "none") {
							$("#comments_count_pid_'.$pid.'").removeClass("clicked");
						} else if ($("#comments_pid_'.$pid.'").css("display") == "block") {
							$("#comments_count_pid_'.$pid.'").addClass("clicked");
						}
					});
				});
				</script>';
	$output .= '</div></div>';
	$output .= ($button == 1 && isset($_SESSION['rid']) && course_belonged($cid,$_SESSION['uid']) && ($uid == $post['User_ID'] || $_SESSION['rid'] != 2)) ? '<form class="post-edit" method="POST" action="/post/edit"><input type="hidden" name="pid" value="'.$post['Post_ID'].'" /><input type="hidden" name="old_cid" value="'.$post['Course_ID'].'" /><input type="hidden" name="old_week" value="'.$post['Post_Week'].'" /><input type="hidden" name="old_title" value="'.$post['Post_Title'].'" /><input type="hidden" name="old_url" value="'.$post['Post_URL'].'" /><input type="hidden" name="old_body" value="'.str_replace('"',"'",$post['Post_Question']).'" /><input name="post_edit" type="submit" value="Edit"/></form><form class="post-delete" method="POST" action="/post/delete"><input type="hidden" name="pid" value="'.$post['Post_ID'].'" /><input title="Delete" name="post_delete" type="submit" value="Delete"/></form>': "";
	return $output;
}
function select_week($name,$week=null) { //Return select element of week numbers
	$output = "";
	$weeks = array('1','2','3','4','5','6','7','8','9','10','11','12');
	$output .= '<select id="'.$name.'" name="'.$name.'">';
	for ($i = 0; $i < count($weeks); $i++) {
		$selected = (($week != null && $week == $weeks[$i]) || ($week == null && (($weeks[$i] == 12 && get_post_week(time()) > 12) || get_post_week(time()) == $weeks[$i]))) ? 'selected': "";
		$output .= '<option '.$selected.' value="'.$weeks[$i].'">'.$weeks[$i].'</option>';
	}
	$output .= '</select>';
	return $output;
}
function list_posts($cid=0,$count=0,$page=1) { //Return list of posts, for lecturer, and admin use
	global $db;
	$output = "";
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	if ($cid != 0) {
		$posts = array_filter($posts, array(new Filter($cid), 'filter_cid'));
	}
	$posts = ($_SESSION['rid'] == 3) ? array_filter($posts, array(new Filter(true), 'filter_course_belonged')) : $posts;
	usort($posts, 'sort_post_date_descend');
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<a class="button" href="/post/create">Create post</a>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<table>';
	$output .= '<tr><th>Title</th><th>Course</th><th>Created time</th><th>Author</th><th colspan="3">Operations</th></tr>';
	$j = 0;
	for ($i = 0; $i < count($posts); $i++) {
		$user = user_load($posts[$i]['User_ID']);
		$course = course_load($posts[$i]['Course_ID']);
		if (isset($_SESSION['rid']) && ($_SESSION['rid'] == $user['rid'] || $_SESSION['rid'] == 1 || $_SESSION['rid'] == 3)) {
			$class = 'class="'.table_row_class($j).'"';
			$output .= '<tr '.$class.'>';
			$output .= '<td><a title="'.$posts[$i]['Post_Title'].'" href="/question/'.$posts[$i]['Post_URL'].'">'.$posts[$i]['Post_Title'].'</a></td>';
			$output .= '<td class="center">'.$course['Course_Code'].'</td>';
			$output .= '<td class="center">'.date('d-m-Y',$posts[$i]['Post_Created']).'</td>';
			$output .= '<td class="center">'.(($posts[$i]['Post_Hide_Name'] == 1 || !user_existed($posts[$i]['User_ID'])) ? 'Anonymous': $user['User_Username']).'</td>';
			$output .= (isset($_SESSION['rid']) && course_belonged($posts[$i]['Course_ID'],$_SESSION['uid']) && ($_SESSION['uid'] == $posts[$i]['User_ID'] || $_SESSION['rid'] != 2) && $_SESSION['rid'] != 1) ? '<td class="center"><form method="POST" action="/post/edit"><input type="hidden" name="pid" value="'.$posts[$i]['Post_ID'].'" /><input type="hidden" name="old_cid" value="'.$posts[$i]['Course_ID'].'" /><input type="hidden" name="old_week" value="'.$posts[$i]['Post_Week'].'" /><input type="hidden" name="old_title" value="'.$posts[$i]['Post_Title'].'" /><input type="hidden" name="old_url" value="'.$posts[$i]['Post_URL'].'" /><input type="hidden" name="old_body" value="'.str_replace('"',"'",$posts[$i]['Post_Question']).'" /><input type="hidden" name="old_answer" value="'.$posts[$i]['Post_Answer'].'" /><input title="Edit" name="post_edit" type="submit" value="Edit"/></form></td>': '<td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && course_belonged($posts[$i]['Course_ID'],$_SESSION['uid']) && ($_SESSION['uid'] == $posts[$i]['User_ID'] || $_SESSION['rid'] != 2) && $_SESSION['rid'] != 1) ? '<td class="center"><form method="POST" action="/triggers/archive_post.php"><input type="hidden" name="pid" value="'.$posts[$i]['Post_ID'].'" /><input title="Archive" name="post_archive" type="submit" value="Archive"/></form></td>': '<td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && course_belonged($posts[$i]['Course_ID'],$_SESSION['uid']) && ($_SESSION['uid'] == $posts[$i]['User_ID'] || $_SESSION['rid'] != 2) || $_SESSION['rid'] == 1) ? '<td class="center"><form method="POST" action="/post/delete"><input type="hidden" name="pid" value="'.$posts[$i]['Post_ID'].'" /><input title="Delete" name="post_delete" type="submit" value="Delete"/></form></td>': '<td class="empty"></td>';
			$output .= '</tr>';
			$j++;
		}
	}
	$output .= '</table>';
	$output .= '<span class="count posts">'.$j.' post'.(($j > 1) ? 's': "").' to display.</span>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#admin_post_section").load("/triggers/admin_post.php",{cid:'.$cid.',count:'.$count.',page:page});
				}
				</script>';
	return $output;
}
function list_archives($cid=0,$count=0,$page=1) { //Return list of archive posts, for lecturer, and admin use
	global $db;
	$output = "";
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(0), 'filter_current'));
	if ($cid != 0) {
		$posts = array_filter($posts, array(new Filter($cid), 'filter_cid'));
	}
	usort($posts, 'sort_post_date_descend');
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<span class="count" colspan="8">'.count($posts).' post'.((count($posts) > 1) ? 's': "").' to display.</span>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<table>';
	$output .= '<tr><th>Title</th><th>Course</th><th>Created time</th><th>Author</th><th colspan="4">Operations</th></tr>';
	for ($i = 0; $i < count($posts); $i++) {
		$user = user_load($posts[$i]['User_ID']);
		$course = course_load($posts[$i]['Course_ID']);
		if (isset($_SESSION['rid']) && ($_SESSION['rid'] == $user['Role_ID'] || $_SESSION['rid'] == 1 || $_SESSION['rid'] == 3)) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'>';
			$output .= '<td><a title="'.$posts[$i]['Post_Title'].'" href="/question/'.$posts[$i]['Post_URL'].'">'.$posts[$i]['Post_Title'].'</a></td>';
			$output .= '<td class="center">'.$course['Course_Code'].'</td>';
			$output .= '<td class="center">'.date('Y-m-d',$posts[$i]['Post_Created']).'</td>';
			$output .= '<td class="center">'.(($posts[$i]['Post_Hide_Name'] == 1 || !user_existed($posts[$i]['User_ID'])) ? 'Anonymous': $user['User_Username']).'</td>';
			$output .= (isset($_SESSION['rid']) && course_belonged($posts[$i]['Course_ID'],$_SESSION['uid']) && ($_SESSION['uid'] == $posts[$i]['User_ID'] || $_SESSION['rid'] != 2) || $_SESSION['rid'] == 1) ? '<td class="center"><form method="POST" action="/post/edit"><input type="hidden" name="pid" value="'.$posts[$i]['Post_ID'].'" /><input type="hidden" name="old_cid" value="'.$posts[$i]['Course_ID'].'" /><input type="hidden" name="old_week" value="'.$posts[$i]['Post_Week'].'" /><input type="hidden" name="old_title" value="'.$posts[$i]['Post_Title'].'" /><input type="hidden" name="old_url" value="'.$posts[$i]['Post_URL'].'" /><input type="hidden" name="old_body" value="'.str_replace('"',"'",$posts[$i]['Post_Question']).'" /><input type="hidden" name="old_answer" value="'.$posts[$i]['Post_Answer'].'" /><input name="post_edit" type="submit" title="Edit" value="Edit"/></form></td>': '<td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && course_belonged($posts[$i]['Course_ID'],$_SESSION['uid']) && ($_SESSION['uid'] == $posts[$i]['User_ID'] || $_SESSION['rid'] != 2) || $_SESSION['rid'] == 1) ? '<td class="center"><form method="POST" action="/triggers/repost.php"><input type="hidden" name="uid" value="'.$_SESSION['uid'].'" /><input type="hidden" name="cid" value="'.$posts[$i]['Course_ID'].'" /><input type="hidden" name="week" value="'.$posts[$i]['Post_Week'].'" /><input type="hidden" name="title" value="'.$posts[$i]['Post_Title'].'" /><input type="hidden" name="url" value="'.repost_url($posts[$i]['Post_URL'],$posts[$i]['Post_ID']).'" /><input type="hidden" name="body" value="'.str_replace('"',"'",$posts[$i]['Post_Question']).'" /><input type="hidden" name="answer" value="'.str_replace('"',"'",$posts[$i]['Post_Answer']).'" /><input type="hidden" name="repostid" value="'.$posts[$i]['Post_ID'].'" /><input name="repost" type="submit" title="Repost" value="Repost"/></form></td>': '<td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && course_belonged($posts[$i]['Course_ID'],$_SESSION['uid']) && ($_SESSION['uid'] == $posts[$i]['User_ID'] || $_SESSION['rid'] != 2) || $_SESSION['rid'] == 1) ? '<td class="center"><form method="POST" action="/triggers/restore_post.php"><input type="hidden" name="pid" value="'.$posts[$i]['Post_ID'].'" /><input name="post_restore" type="submit" title="Restore" value="Restore"/></form></td>': '<td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && course_belonged($posts[$i]['Course_ID'],$_SESSION['uid']) && ($_SESSION['uid'] == $posts[$i]['User_ID'] || $_SESSION['rid'] != 2) || $_SESSION['rid'] == 1) ? '<td class="center"><form method="POST" action="/post/delete"><input type="hidden" name="pid" value="'.$posts[$i]['Post_ID'].'" /><input name="post_delete" type="submit" title="Delete" value="Delete"/></form></td>': '<td class="empty"></td>';
			$output .= '</tr>';
		}
	}
	$output .= '</table>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#admin_archive_section").load("/triggers/admin_archive.php",{cid:'.$cid.',count:'.$count.',page:page});
				}
				</script>';
	return $output;
}
function create_post($uid,$cid,$week,$title,$url,$body,$answer,$hide=0) { //Create post
	global $db;
	$body = strip_tags($body);
	$answer = strip_tags($answer);
	$title = $title;
	$url = $url;
	$body = convert_link($body);
	$answer = convert_link($answer);
	$array = array(
				'User_ID' => $uid,
				'Course_ID' => $cid,
				'Post_Week' => $week,
				'Post_Title' => $title,
				'Post_URL' => $url,
				'Post_Question' => $body,
				'Post_Answer' => $answer,
				'Post_Hide_Name' => $hide,
				'Post_Created' => time()
			);
	$db->insert_record($array,'POST');
}
function edit_post($pid,$cid,$week,$title,$url,$body,$answer) { //Edit post details including course ID, week number, post title, post URL alias, post body, post answer, and updated timestamp
	global $db;
	$body = strip_tags($body);
	$answer = strip_tags($answer);
	$title = $title;
	$url = $url;
	$body = $body;
	$answer = $answer;
	$array = array(
				'Course_ID' => $cid,
				'Post_Week' => $week,
				'Post_Title' => $title,
				'Post_URL' => $url,
				'Post_Question' => $body,
				'Post_Answer' => $answer,
				'Post_Edited' => time()
			);
	$db->update_record($array,'Post_ID',$pid,'POST');
}
function delete_post($pid) { //Delete post by post ID
	global $db;
	$db->delete_record('Post_ID',$pid,'POST');
}
function archive_post($pid) { //Put post into archive from post ID
	global $db;
	$array = array(
				'Repost_ID' => $pid,
				'Post_Current' => '0'
			);
	$db->update_record($array,'Post_ID',$pid,'POST');
}
function restore_post($pid) { //Put post into archive from post ID
	global $db;
	$array = array(
				'Post_Current' => '1'
			);
	$db->update_record($array,'Post_ID',$pid,'POST');
}
function repost($uid,$cid,$week,$title,$url,$body,$answer,$repostid) { //Repost an old post in the archive
	global $db;
	$body = strip_tags($body);
	$answer = strip_tags($answer);
	$title = $title;
	$url = $url;
	$body = $body;
	$answer = $answer;
	$array = array(
				'User_ID' => $uid,
				'Course_ID' => $cid,
				'Post_Week' => $week,
				'Post_Title' => $title,
				'Post_URL' => $url,
				'Post_Question' => $body,
				'Post_Answer' => $answer,
				'Repost_ID' => $repostid,
				'Post_Created' => time()
			);
	$db->insert_record($array,'POST');
}
function repost_url($url,$repostid) { //Return repost's URL
	global $db;
	$reposts = $db->array_load('POST','Repost_ID',$repostid);
	$count = count($reposts)-1;
	return $url.'-'.$count;
}
function delete_post_vote_from_pid($pid) { //Delete all likes and dislikes of post
	global $db;
	$db->delete_record('Post_ID',$pid,'POST_VOTE');
}
function delete_post_follow_from_pid($pid) { //Delete all follows of post
	global $db;
	$db->delete_record('Post_ID',$pid,'POST_FOLLOW');
}
/* Course Belonged function */
function course_belonged($cid,$uid) {
	$uids = uids_load_from_cid($cid);
	if (in_array($uid,$uids)) {
		return true;
	} else {
		return false;
	}
}
/* Is Enroled function */
function is_enroled($uid) {
	$courses = courses_load_from_uid($uid);
	if (count($courses) > 0) {
		return true;
	} else {
		return false;
	}
}
/* Is Allowed function */
function is_allowed($uid) {
	$courses = courses_load_from_uid($uid);
	$courses = array_filter($courses, array(new Filter(1), 'filter_course_allowed'));
	if (count($courses) > 0) {
		return true;
	} else {
		return false;
	}
}
/* Course Functions */
function view_course($cid,$uid,$count,$page=1) { //Return course details with feed of posts
	$output = "";
	$posts = posts_load_from_cid($cid);
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	usort($posts, 'sort_post_date_descend');
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<div class="course posts">';
	for ($i = 0; $i < count($posts); $i++) {
		if (isset($posts[$i])) {
			$pid = $posts[$i]['Post_ID'];
			$output .= view_post($pid,$uid);
			$output .= ($i != (count($posts)-1)) ? '<hr>': "";
		}
	}
	$output .= '</div>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#feeds").load("/triggers/paging_course.php",{cid:'.$cid.',count:'.$count.',page:page});
				}
				setInterval(function(){
					$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"course",cid:'.$cid.',page:'.$page.'});
				},1000*60*5);
				</script>';
	if (count($posts) == 0) {
		$output .= '<h3>This course has no post. Be the first to ask question.</h3>';
	}
	return $output;
}
function view_user_courses($uid) { //Return courses list by user ID
	$output = "";
	$course_user_cids = array();
	$courses = array();
	$course_users = course_users_load($uid);
	foreach ($course_users as $course_user) {
		$course_user_cids[] = $course_user['Course_ID'];
	}
	sort($course_user_cids);
	foreach ($course_user_cids as $course_user_cid) {
		$courses[] = course_load($course_user_cid);
	}
	usort($courses,'sort_course_code_ascend');
	if (count($courses) > 0) {
		$output .= '<ul class="user_course">';
		for ($i = 0; $i < count($courses); $i++) {
			if (isset($courses[$i])) {
				$course = course_load($courses[$i]['Course_ID']);
				$users = users_load_by_cid($courses[$i]['Course_ID']);
				$output .= '<li class="course">';
				$output .= '<a title="'.$course['Course_Name'].'" class="cid-'.$course['Course_ID'].' course_code" href="/course/'.$course['Course_Code'].'">'.$course['Course_Code'].' - '.$course['Course_Name'].'</a>';
				$output .= '</li>';
			}
		}
		$output .= '</ul>';
	}
	return $output;
}
function view_course_lecturers($cid) { //Return lecturers list by course ID
	$output = "";
	$lecturers = lecturers_load_from_cid($cid);
	sort($lecturers);
	$course = course_load($cid);
	if (count($lecturers) > 0) {
		$output .= '<ul class="course_lecturer">';
		for ($i = 0; $i < count($lecturers); $i++) {
			if (isset($lecturers[$i])) {
				$output .= '<li class="lecturer">';
				$output .= '<a title="'.$lecturers[$i]['User_Fullname'].'" class="lecturer_name" href="/user/'.$lecturers[$i]['User_Username'].'">'.$lecturers[$i]['User_Fullname'].(($course['User_ID'] == $lecturers[$i]['User_ID']) ? ' - Coordinator': "").'</a>';
				$output .= '</li>';
			}
		}
		$output .= '</ul>';
	}
	return $output;
}
function view_courses_by_uid($uid) { //Return courses list by user ID
	$output = "";
	$course_user_cids = array();
	$courses = array();
	$course_users = course_users_load($uid);
	foreach ($course_users as $course_user) {
		$course_user_cids[] = $course_user['Course_ID'];
	}
	if ($_SESSION['rid'] == 1) {
		$course_user_cids = cids_load_all();
	}
	sort($course_user_cids);
	foreach ($course_user_cids as $course_user_cid) {
		$courses[] = course_load($course_user_cid);
	}
	usort($courses,'sort_course_code_ascend');
	if (count($courses) > 0) {
		$output .= '<div class="heading">My courses</div>';
		$output .= '<ul>';
		for ($i = 0; $i < count($courses); $i++) {
			if (isset($courses[$i])) {
				$course = course_load($courses[$i]['Course_ID']);
				$users = users_load_by_cid($courses[$i]['Course_ID']);
				$output .= '<li class="course">';
				$output .= '<a title="'.$course['Course_Name'].'" class="cid-'.$course['Course_ID'].' course_code" href="/course/'.$course['Course_Code'].'">'.$course['Course_Code'].'</a><br/>';
				$output .= '<span class="course_name">'.$course['Course_Name'].'</span>';
				$output .= '</li>';
			}
		}
		$output .= '</ul>';
	}
	return $output;
}
function view_other_courses_by_uid($uid) { //Return other courses list by user ID
	global $db;
	$output = "";
	$course_cids = array();
	$course_user_cids = array();
	$course_diff_cids = array();
	$courses_diff = array();
	$courses = $db->array_load_all('COURSE');
	$course_users = course_users_load($uid);
	foreach ($courses as $course) {
		$course_cids[] = $course['Course_ID'];
	}
	foreach ($course_users as $course_user) {
		$course_user_cids[] = $course_user['Course_ID'];
	}
	$course_diff_cids = array_diff($course_cids,$course_user_cids);
	sort($course_user_cids);
	sort($course_diff_cids);
	foreach ($course_diff_cids as $course_diff_cid) {
		$courses_diff[] = course_load($course_diff_cid);
	}
	usort($courses_diff,'sort_course_code_ascend');
	if (count($courses_diff) > 0) {
		$output .= '<div class="heading">Other courses</div>';
		$output .= '<a id="course_name_toggle">More details</a>';
		$output .= '<ul id="other_courses">';
		for ($i = 0; $i < count($courses_diff); $i++) {
			$course = course_load($courses_diff[$i]['Course_ID']);
			$output .= '<li class="course">';
			$output .= '<a title="'.$course['Course_Name'].'" class="cid-'.$course['Course_ID'].' course_code" href="/course/'.$course['Course_Code'].'">'.$course['Course_Code'].'</a><br/>';
			$output .= '<span class="course_name">'.$course['Course_Name'].'</span>';
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '<script>
					$("#course_name_toggle").click(function(){
						if (!$(this).hasClass("clicked")) {
							$("#leftmenu ul#other_courses li.course span.course_name").css("display","block");
							$(this).text("Less details").addClass("clicked");
						} else if ($(this).hasClass("clicked")) {
							$("#leftmenu ul#other_courses li.course span.course_name").css("display","none");
							$(this).text("More details").removeClass("clicked");
						}
					});
					</script>';
	}
	if ($_SESSION['rid'] == 1) {
		$output = "";
	}
	return $output;
}
function select_course($name,$cid = null) { //Return select element of course IDs
	global $db;
	$output = "";
	$courses = $db->array_load_all('COURSE');
	$output .= '<select id="'.$name.'" name="'.$name.'">';
	$output .= (isset($_GET['p']) && ($_GET['p'] == 'post' || $_GET['p']) == 'post/archive' || (isset($_POST['report_type']) && ($_POST['report_type'] == 'Number of questions per week' || $_POST['report_type'] == 'Most popular questions' || $_POST['report_type'] == 'Most difficult questions'))) ? '<option value="0">All courses</option>': "";
	for ($i = 0; $i < count($courses); $i++) {
		if (($_SESSION['rid'] == 2 && course_belonged($courses[$i]['Course_ID'],$_SESSION['uid']) && $courses[$i]['Course_Allowed'] == 1) || ($_SESSION['rid'] == 3 && course_belonged($courses[$i]['Course_ID'],$_SESSION['uid'])) || (isset($_SESSION['rid']) && $courses[$i]['Course_For_Guest'] == 1) || $_SESSION['rid'] == 1) {
			$selected = ($cid != null && $cid == $courses[$i]['Course_ID']) ? 'selected': "";
			$output .= '<option '.$selected.' value="'.$courses[$i]['Course_ID'].'">'.$courses[$i]['Course_Code'].' - '.$courses[$i]['Course_Name'].'</option>';
		}
	}
	$output .= '</select>';
	return $output;
}
function checkbox_course($name,$uid) { //Return checkbox element of course IDs
	global $db;
	$output = "";
	$course_cids = array();
	$course_user_cids = array();
	$course_diff_cids = array();
	$courses = $db->array_load_all('COURSE');
	$course_users = course_users_load($uid);
	foreach ($courses as $course) {
		$course_cids[] = $course['Course_ID'];
	}
	foreach ($course_users as $course_user) {
		$course_user_cids[] = $course_user['Course_ID'];
	}
	$course_diff_cids = array_diff($course_cids,$course_user_cids);
	sort($course_user_cids);
	sort($course_diff_cids);
	for ($i = 0; $i < count($course_user_cids); $i++) {
		$course_user = course_load($course_user_cids[$i]);
		$output .= (isset($course_user_cids[$i])) ? '<input type="checkbox" name="'.$name.'" checked value="'.$course_user['Course_ID'].'" />'.$course_user['Course_Name'].'<br/>' : "";
	}
	for ($i = 0; $i < count($course_diff_cids); $i++) {
		$course_diff = course_load($course_diff_cids[$i]);
		$output .= (isset($course_diff_cids[$i])) ? '<input type="checkbox" name="'.$name.'" value="'.$course_diff['Course_ID'].'" />'.$course_diff['Course_Name'].'<br/>' : "";
	}
	$output .= '</select>';
	return $output;
}
function list_courses() { //Return list of courses, for admin use
	global $db;
	$output = "";
	$courses = $db->array_load_all('COURSE');
	$cids = user_cids_load_all($_SESSION['uid']);
	$output .= ($_SESSION['rid'] == 1) ? '<a class="button" href="/course/create">Create course</a>': "";
	$output .= '<table>';
	$output .= '<tr><th>Course code</th><th>Course name</th><th>Lecturers</th><th colspan="8">Operations</th></tr>';
	$j = 0;
	for ($i = 0; $i < count($courses); $i++) {
		$cid = $courses[$i]['Course_ID'];
		if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1 || in_array($cid,$cids)) {
			$class = 'class="'.table_row_class($j).'"';
			$output .= '<tr '.$class.'>';
			$output .= '<td class="center">'.$courses[$i]['Course_Code'].'</td>';
			$output .= '<td>'.$courses[$i]['Course_Name'].'</td>';
			$output .= '<td>'.view_course_lecturers($cid).'</td>';
			$output .= (isset($_SESSION['rid']) && $_SESSION['rid'] == 3 && in_array($cid,$cids)) ? '<td class="center"><form action="/course/enrol" method="post"><input type="hidden" name="cid" value="'.$cid.'" /><input type="submit" name="course_enrol" title="Enrol students manually" value="Enrol students manually" /></form></td><td class="center"><form action="/course/csv" method="post"><input type="hidden" name="cid" value="'.$cid.'" /><input type="submit" name="course_csv" title="Enrol students by importer" value="Enrol students by importer" /></form></td><td class="center"><form action="/triggers/course_post_allow.php" method="post"><input type="hidden" name="cid" value="'.$cid.'" /><input type="submit" name="'.(($courses[$i]['Course_Allowed'] == 1) ? 'course_allow': 'course_not_allow').'" title="'.(($courses[$i]['Course_Allowed'] == 0) ? 'Allow': 'Not allow').' post" value="'.(($courses[$i]['Course_Allowed'] == 0) ? 'Allow': 'Not allow').' post" /></form></td><td class="center"><form action="/triggers/course_for_guest.php" method="post"><input type="hidden" name="cid" value="'.$cid.'" /><input type="submit" name="'.(($courses[$i]['Course_For_Guest'] == 1) ? 'course_guest_on': 'course_guest_off').'" title="'.(($courses[$i]['Course_For_Guest'] == 0) ? 'Turn on': 'Turn off').' guest mode" value="'.(($courses[$i]['Course_For_Guest'] == 0) ? 'Turn on': 'Turn off').' guest mode" /></form></td>': '<td class="empty"></td><td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && ($_SESSION['rid'] == 3 && $_SESSION['uid'] == $courses[$i]['User_ID'] && in_array($cid,$cids)) || $_SESSION['rid'] == 1) ? '<td class="center"><form action="/course/assign" method="post"><input type="hidden" name="cid" value="'.$cid.'" /><input type="submit" name="course_assign" title="Assign lecturers" value="Assign lecturers" /></form></td>': '<td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && $_SESSION['rid'] == 1 && count_lecturers($cid) > 0) ? '<td class="center"><form action="/course/promote" method="post"><input type="hidden" name="cid" value="'.$cid.'" /><input type="submit" name="course_promote" title="Promote coordinator" value="Promote coordinator" /></form></td>': '<td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && ($_SESSION['rid'] == 3 && $_SESSION['uid'] == $courses[$i]['User_ID'] && in_array($cid,$cids)) || $_SESSION['rid'] == 1) ? '<td class="center"><form method="POST" action="/course/edit"><input type="hidden" name="cid" value="'.$cid.'" /><input type="hidden" name="code" value="'.$courses[$i]['Course_Code'].'" /><input type="hidden" name="name" value="'.$courses[$i]['Course_Name'].'" /><input name="course_edit" type="submit" title="Edit" value="Edit"/></form></td>': '<td class="empty"></td>';
			$output .= (isset($_SESSION['rid']) && $_SESSION['rid'] == 1) ? '<td class="center"><form method="POST" action="/course/delete"><input type="hidden" name="cid" value="'.$cid.'" /><input name="course_delete" type="submit" title="Delete" value="Delete"/></form></td>': '<td class="empty"></td>';
			$output .= '</tr>';
			$j++;
		}
	}
	$output .= '</table>';
	$output .= '<span class="count" colspan="8">'.$j.' course'.(($j > 1) ? 's': "").' to display.</span>';
	return $output;
}
function create_course($code,$name) { //Create course
	global $db;
	$array = array(
				'Course_Code' => $code,
				'Course_Name' => $name
			);
	$db->insert_record($array,'COURSE');
}
function edit_course($cid,$code,$name) { //Edit course details including course code and course name
	global $db;
	$array = array(
				'Course_Code' => $code,
				'Course_Name' => $name
			);
	$db->update_record($array,'Course_ID',$cid,'COURSE');
}
function delete_course($cid) { //Delete course
	global $db;
	$db->delete_record('Course_ID',$cid,'COURSE');
	delete_all_user_course($cid);
}
function allow_course_post($cid) { //Allow students to post question in course
	global $db;
	$array = array(
				'Course_Allowed' => '1'
			);
	$db->update_record($array,'Course_ID',$cid,'COURSE');
}
function disallow_course_post($cid) { //Disallow students to post questions in course
	global $db;
	$array = array(
				'Course_Allowed' => '0'
			);
	$db->update_record($array,'Course_ID',$cid,'COURSE');
}
function turn_on_course_guest($cid) { //Turn on guest mode
	global $db;
	$array = array(
				'Course_For_Guest' => '1'
			);
	$db->update_record($array,'Course_ID',$cid,'COURSE');
}
function turn_off_course_guest($cid) { //Turn off guest mode
	global $db;
	$array = array(
				'Course_For_Guest' => '0'
			);
	$db->update_record($array,'Course_ID',$cid,'COURSE');
}
function course_is_empty($cid) { //Check if course is empty
	global $db;
	$posts = $db->array_load('POST','Course_ID',$cid);
	if (count($posts) == 0) {
		return true;
	} elseif (count($posts) > 0) {
		return false;
	}
}
/* Course_Users Functions */
function checkbox_course_user($name,$uid,$cid=null) { //Return checkbox element of course IDs from course_users table
	$output = "";
	$course_users = course_users_load($uid);
	for ($i = 0; $i < count($course_users); $i++) {
		$course = course_load($course_users[$i]['Course_ID']);
		$checked = ($cid != null && $cid == $course_users[$i]['Course_ID']) ? 'checked': "";
		$output .= '<input name="'.$name.'" type="checkbox" '.$checked.' value="'.$course_users[$i]['Course_ID'].'" />'.$course['Course_Name'].'<br/>';
	}
	return $output;
}
function create_course_user($cid,$uid) { //Create new record in table course_users
	global $db;
	$array = array(
				'Course_ID' => $cid,
				'User_ID' => $uid
			);
	$db->insert_record($array,'USER_COURSE');
}
function delete_course_user($cid,$uid) { //Delete record in table course_users
	global $db;
	$db->delete_record_with_two_identifier('Course_ID',$cid,'User_ID',$uid,'USER_COURSE');
}
function delete_all_course_user($uid) { //Delete all records in table course_users by user ID
	global $db;
	$db->delete_record('User_ID',$uid,'USER_COURSE');
}
function delete_all_user_course($cid) { //Delete all records in table course_users by course ID
	global $db;
	$db->delete_record('Course_ID',$cid,'USER_COURSE');
}
/* Post Follow Functions */
function follow_post($uid,$pid) { //Follow a post
	global $db;
	$array = array(
				'User_ID' => $uid,
				'Post_ID' => $pid
			);
	$db->insert_record($array,'POST_FOLLOW');
}
function unfollow_post($uid,$pid) { //Unfollow a post
	global $db;
	$db->delete_record_with_two_identifier('User_ID',$uid,'Post_ID',$pid,'POST_FOLLOW');
}
function follow_notify($pid,$commenter_name,$comment) { //Send notification emails to followers for every new comment
	$uids = array();
	$post_follows = post_follows_load_by_pid($pid);
	foreach ($post_follows as $follower) {
		$uids[] = $follower['User_ID'];
	}
	foreach ($uids as $uid) {
		$post = post_load($pid);
		$user = user_load($uid);
		$to = $user['User_Mail'];
		$subject = 'Notification for the post "'.$post['Post_Title'].'"';
		$from = 'okms.vietnam@gmail.com';
		send_mail($to,$subject,'
<table style="border: 1px solid black;">
	<tr style="border: 1px solid black;">
		<td>
			<img src="'.currentURL().'/images/banner_email.png" width="480" height="80" />
		</td>
	</tr>
	<tr style="border: 1px solid black;">
		<td>
			<p>Hi <b>'.$user['User_Fullname'].'</b></p>
			<p>'.$commenter_name.' commented on the post that you have followed</p>
			<p>'.$commenter_name.' wrote: "'.$comment.'"</p>
			<p><a href="'.currentURL().'/question/'.$post['Post_URL'].'">Go to comments</a> now</p>
		</td>
	</tr>
</table>
		',$from);
	}
}
/* Post Rate Functions */
function select_post_rate($name) { //Return select element of post rates
	$output = "";
	$rate_labels = array('Very Easy','Easy','Normal','Difficult','Very Difficult');
	$output .= '<select id="'.$name.'" name="'.$name.'">';
	for ($i = 0; $i < 5; $i++) {
		$output .= '<option value="'.($i+1).'">'.$rate_labels[$i].'</option>';
	}
	$output .= '</select>';
	return $output;
}
function star_rating($pid) {
	$output = "";
	$post = post_load($pid);
	$cid = $post['Course_ID'];
	$course = course_load($cid);
	$output .= '<div title="Rate this post" id="post_rate_pid_'.$pid.'" class="rate_widget">';
	$output .= '<div id="rate_1_pid_'.$pid.'" class="ratings_stars'.'"></div>';
	$output .= '<div id="rate_2_pid_'.$pid.'" class="ratings_stars'.'"></div>';
	$output .= '<div id="rate_3_pid_'.$pid.'" class="ratings_stars'.'"></div>';
	$output .= '<div id="rate_4_pid_'.$pid.'" class="ratings_stars'.'"></div>';
	$output .= '<div id="rate_5_pid_'.$pid.'" class="ratings_stars'.'"></div>';
	$output .= '<span class="post_rate_text" id="rate_text_pid_'.$pid.'"></span>';
	$output .= '</div>';
	$output .= '<div title="Average rating: '.average_post_rates_with_decimal($pid,1).'" id="average_post_rate_pid_'.$pid.'" class="average_rate">'.star_rating_average($pid).'</div>';
	$output .= '<div id="save_post_rate_pid_'.$pid.'"></div>';
	$output .= '<script>
				$("#average_post_rate_pid_'.$pid.'").load("/triggers/post_rate_average.php",{pid:'.$pid.'});
				$("#rate_1_pid_'.$pid.'").mouseenter(function(){
					$(this).addClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("It is easy");
				}).mouseleave(function(){
					$(this).removeClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("");
				});
				$("#rate_2_pid_'.$pid.'").mouseenter(function(){
					$("#rate_1_pid_'.$pid.'").addClass("ratings_over");
					$(this).addClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("Not challenge");
				}).mouseleave(function(){
					$("#rate_1_pid_'.$pid.'").removeClass("ratings_over");
					$(this).removeClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("");
				});
				$("#rate_3_pid_'.$pid.'").mouseenter(function(){
					$("#rate_1_pid_'.$pid.'").addClass("ratings_over");
					$("#rate_2_pid_'.$pid.'").addClass("ratings_over");
					$(this).addClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("Normal question");
				}).mouseleave(function(){
					$("#rate_1_pid_'.$pid.'").removeClass("ratings_over");
					$("#rate_2_pid_'.$pid.'").removeClass("ratings_over");
					$(this).removeClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("");
				});
				$("#rate_4_pid_'.$pid.'").mouseenter(function(){
					$("#rate_1_pid_'.$pid.'").addClass("ratings_over");
					$("#rate_2_pid_'.$pid.'").addClass("ratings_over");
					$("#rate_3_pid_'.$pid.'").addClass("ratings_over");
					$(this).addClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("A bit challenge");
				}).mouseleave(function(){
					$("#rate_1_pid_'.$pid.'").removeClass("ratings_over");
					$("#rate_2_pid_'.$pid.'").removeClass("ratings_over");
					$("#rate_3_pid_'.$pid.'").removeClass("ratings_over");
					$(this).removeClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("");
				});
				$("#rate_5_pid_'.$pid.'").mouseenter(function(){
					$("#rate_1_pid_'.$pid.'").addClass("ratings_over");
					$("#rate_2_pid_'.$pid.'").addClass("ratings_over");
					$("#rate_3_pid_'.$pid.'").addClass("ratings_over");
					$("#rate_4_pid_'.$pid.'").addClass("ratings_over");
					$(this).addClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("This is hard");
				}).mouseleave(function(){
					$("#rate_1_pid_'.$pid.'").removeClass("ratings_over");
					$("#rate_2_pid_'.$pid.'").removeClass("ratings_over");
					$("#rate_3_pid_'.$pid.'").removeClass("ratings_over");
					$("#rate_4_pid_'.$pid.'").removeClass("ratings_over");
					$(this).removeClass("ratings_over");
					$("#rate_text_pid_'.$pid.'").text("");
				});
				$("#rate_1_pid_'.$pid.'").click(function(){
					starRating('.$pid.',1);
				});
				$("#rate_2_pid_'.$pid.'").click(function(){
					starRating('.$pid.',2);
				});
				$("#rate_3_pid_'.$pid.'").click(function(){
					starRating('.$pid.',3);
				});
				$("#rate_4_pid_'.$pid.'").click(function(){
					starRating('.$pid.',4);
				});
				$("#rate_5_pid_'.$pid.'").click(function(){
					starRating('.$pid.',5);
				});
				</script>';
	return $output;
}
function star_rating_average($pid) {
	$output = "";
	$output .= '<div id="average_rate_1_pid_'.$pid.'" class="average_ratings_stars no_cursor"></div>';
	$output .= '<div id="average_rate_2_pid_'.$pid.'" class="average_ratings_stars no_cursor"></div>';
	$output .= '<div id="average_rate_3_pid_'.$pid.'" class="average_ratings_stars no_cursor"></div>';
	$output .= '<div id="average_rate_4_pid_'.$pid.'" class="average_ratings_stars no_cursor"></div>';
	$output .= '<div id="average_rate_5_pid_'.$pid.'" class="average_ratings_stars no_cursor"></div>';
	$output .= '<a class="details_rate_trigger" id="details_rate_trigger_pid_'.$pid.'">Details</a>';
	$output .= '<div id="average_rate_pid_'.$pid.'">'.average_post_rates($pid).'</div>';
	$output .= '<div class="details_rate" id="details_rate_pid_'.$pid.'"></div>';
	$output .= '<script>
				var averageRate = $("#average_rate_pid_'.$pid.'").text();
				if (averageRate == 1) {
					$("#average_rate_1_pid_'.$pid.'").attr("class","average_ratings_vote");
				} else if (averageRate == 2) {
					$("#average_rate_1_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_2_pid_'.$pid.'").attr("class","average_ratings_vote");
				} else if (averageRate == 3) {
					$("#average_rate_1_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_2_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_3_pid_'.$pid.'").attr("class","average_ratings_vote");
				} else if (averageRate == 4) {
					$("#average_rate_1_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_2_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_3_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_4_pid_'.$pid.'").attr("class","average_ratings_vote");
				} else if (averageRate == 5) {
					$("#average_rate_1_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_2_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_3_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_4_pid_'.$pid.'").attr("class","average_ratings_vote");
					$("#average_rate_5_pid_'.$pid.'").attr("class","average_ratings_vote");
				}
				$("#details_rate_trigger_pid_'.$pid.'").click(function(){
					$("#wrap-content").load("/triggers/post_rate_details.php",{pid:'.$pid.'});
					openEmptyWrap();
				});
				</script>';
	return $output;
}
function star_rating_update($pid) {
	$output = "";
	$post_rate = post_rate_load($pid,$_SESSION['uid']);
	$output .= '<div id="rate_1_pid_'.$pid.'" class="ratings_stars no_cursor"></div>';
	$output .= '<div id="rate_2_pid_'.$pid.'" class="ratings_stars no_cursor"></div>';
	$output .= '<div id="rate_3_pid_'.$pid.'" class="ratings_stars no_cursor"></div>';
	$output .= '<div id="rate_4_pid_'.$pid.'" class="ratings_stars no_cursor"></div>';
	$output .= '<div id="rate_5_pid_'.$pid.'" class="ratings_stars no_cursor"></div>';
	$output .= '<span class="post_rate_text" id="rate_text_pid_'.$pid.'"></span>';
	$output .= '<div id="user_rate_pid_'.$pid.'">'.$post_rate['PostRate'].'</div>';
	$output .= '<script>
				var userRate = $("#user_rate_pid_'.$pid.'").text();
				if (userRate == 1) {
					$("#rate_1_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_text_pid_'.$pid.'").text("It is easy");
				} else if (userRate == 2) {
					$("#rate_1_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_2_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_text_pid_'.$pid.'").text("Not challenge");
				} else if (userRate == 3) {
					$("#rate_1_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_2_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_3_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_text_pid_'.$pid.'").text("Normal question");
				} else if (userRate == 4) {
					$("#rate_1_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_2_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_3_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_4_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_text_pid_'.$pid.'").text("A bit challenge");
				} else if (userRate == 5) {
					$("#rate_1_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_2_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_3_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_4_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_5_pid_'.$pid.'").attr("class","ratings_vote");
					$("#rate_text_pid_'.$pid.'").text("This is hard");
				}
				</script>';
	return $output;
}
function post_rate_details($pid) {
	$output = "";
	$post = post_load($pid);
	$rates = array('1','2','3','4','5');
	$rate_descriptions = array('It is easy','Not challenge','Normal question','A bit challenge','This is hard');
	$output .= '<h5>Rating details for the post "'.$post['Post_Title'].'"</h5><div id="chart_rate_details_pid_'.$pid.'"></div>';
	$output .= '<script>
				(function() {
					YUI().use("charts", function (Y)
					{
						var myDataValues = [
			   ';
	for ($i = 0; $i < count($rates); $i++) {
		$output .= '
							{rate: "'.$rate_descriptions[$i].'", "Number of rates": '.count_post_rate($pid,$rates[$i]).(($i != (count($rates)-1)) ? '},': '}');
	}
	$output .= '		];
						var myAxes = {
							values:{
								position:"bottom",
								type:"numeric",
								minimum: 0,
								roundingMethod: "niceNumber",
								alwaysShowZero:true
							}
						};
						var mychart = new Y.Chart({
						dataProvider:myDataValues, 
						render:"#chart_rate_details_pid_'.$pid.'",
						type: "bar",
						axes:myAxes,
						categoryKey:"rate"
						});
					});
				})();
				</script>';
	return $output;
}
function rate_post($uid,$pid,$rate) { //Rate a post
	global $db;
	$array = array(
				'User_ID' => $uid,
				'Post_ID' => $pid,
				'PostRate' => $rate
			);
	$db->insert_record($array,'POST_RATE');
}
function unrate_post($uid,$pid) { //Un-rate a post
	global $db;
	$db->delete_record_with_two_identifier('User_ID',$uid,'Post_ID',$pid,'POST_RATE');
}
function delete_post_rate_from_pid($pid) { //Delete rating of post
	global $db;
	$db->delete_record('Post_ID',$pid,'POST_RATE');
}
/* Front page listing functions */
function front_page_listing($count,$uid,$sort_type,$option,$page=1) { //Return list of posts on front page
	global $db;
	$output = "";	
	$default = DEFAULT_AVATAR;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	if ($option == 'All courses') {
		$posts = $posts;
	} elseif ($option == 'My courses') {
		$posts = array_filter($posts, array(new Filter(true), 'filter_course_belonged'));
	} elseif ($option == 'Other courses') {
		$posts = array_filter($posts, array(new Filter(false), 'filter_course_belonged'));
	}
	sort($posts);
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['comments_count'] = count_comments($posts[$p]['Post_ID']);
	}
	usort($posts,$sort_type);
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<div class="posts">';
	for ($i = 0; $i < count($posts); $i++) {
		if ($posts[$i]['Post_ID']) {
			$pid = $posts[$i]['Post_ID'];
			$output .= view_post($pid,$uid);
			$output .= ($i != (count($posts)-1)) ? '<hr>': "";
		}
	}
	$output .= '</div>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#feeds").load("/triggers/paging_front.php",{count:'.$count.',option:"'.$option.'",page:page});
				}
				$("select#option").change(function(){
					$("#feeds").load("/triggers/filter_front.php",{option:$(this).val()});
				});
				setInterval(function(){
					$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"front",page:'.$page.'});
				},1000*60*5);
				</script>';
	return $output;
}
function select_front_page_filter($name,$option=null) { //Return select element of front page filter options
	$output = "";
	$options = array('All courses','My courses','Other courses');
	$output .= '<select id="'.$name.'" name="'.$name.'">';
	for ($i = 0; $i < count($options); $i++) {
		$selected = ($option != null && $option == $options[$i]) ? 'selected': "";
		$output .= '<option '.$selected.' value="'.$options[$i].'">'.$options[$i].'</option>';
	}
	$output .= '</select>';
	return $output;
}
/* Latest Function */
function latest_questions($count) {
	global $db;
	$output = "";
	$default = DEFAULT_AVATAR;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	usort($posts,'sort_post_date_descend');
	$output .= '<div class="heading">Latest Questions</div>';
	for ($i = 0; $i < $count; $i++) {
		if ($posts[$i]) {
			$pid = $posts[$i]['Post_ID'];
			$post = post_load($pid);
			$user = user_load($posts[$i]['User_ID']);
			$title = $posts[$i]['Post_Title'];
			$course = course_load($posts[$i]['Course_ID']);
			$email = $user['User_Mail'];
			$size = 40;
			$grav_url = "https://0.gravatar.com/avatar/" . md5( strtolower( trim((string) $email ) ) ) . "?d=identicon&s=" . $size;
			$output .= '<div class="post '.(($i == 0) ? 'first': "").'">';
			$output .= '<a class="author" href="'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? '#': '/user/'.$user['User_Username']).'"><img alt="'.$user['User_Username'].'" src="'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? $default: $grav_url).'" width="40px" height="40px"/></a>';
			$output .= '<div class="post_right_detail">';
			$output .= '<a class="title" href="/question/'.$post['Post_URL'].'">'.$title.'</a><br/>';
			$output .= '<span class="author_name">'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? "": '<a href="/user/'.$user['User_Username'].'">').((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? 'Anonymous': ((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username'])).((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? "": '</a>').'</span><br/>';
			$output .= '<a class="course_name" href="/course/'.$course['Course_Code'].'">'.$course['Course_Code'].'</a>';
			// $output .= '<a class="post_see_more" href="/question/'.$post['Post_URL'].'">See more</a>';
			$output .= '</div>';
			$output .= '</div>';
		}
	}
	$output .= '<br/>';
	return $output;
}
/* Most Commented Function */
function most_commented($count) {
	global $db;
	$output = "";
	$default = DEFAULT_AVATAR;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	sort($posts);
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['comments_count'] = count_comments($posts[$p]['Post_ID']);
	}
	usort($posts,'sort_comments_count_descend');
	$output .= '<div class="heading">Most Commented</div>';
	for ($i = 0; $i < $count; $i++) {
		if ($posts[$i]) {
			$pid = $posts[$i]['Post_ID'];
			$post = post_load($pid);
			$user = user_load($posts[$i]['User_ID']);
			$title = $posts[$i]['Post_Title'];
			$course = course_load($posts[$i]['Course_ID']);
			$email = $user['User_Mail'];
			$size = 40;
			$grav_url = "https://0.gravatar.com/avatar/" . md5( strtolower( trim((string) $email ) ) ) . "?d=identicon&s=" . $size;
			$output .= '<div class="post '.(($i == 0) ? 'first': "").'">';
			$output .= '<a class="author" href="'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? '#': '/user/'.$user['User_Username']).'"><img alt="'.$user['User_Username'].'" src="'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? $default: $grav_url).'" width="40px" height="40px"/></a>';
			$output .= '<div class="post_right_detail">';
			$output .= '<a class="title" href="/question/'.$post['Post_URL'].'">'.$title.'</a><br/>';
			$output .= '<span class="author_name">'.((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? "": '<a href="/user/'.$user['User_Username'].'">').((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? 'Anonymous': ((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username'])).((isset($post['Post_Hide_Name']) && $post['Post_Hide_Name'] == 1 || !user_existed($post['User_ID'])) ? "": '</a>').'</span><br/>';
			$output .= '<a class="course_name" href="/course/'.$course['Course_Code'].'">'.$course['Course_Code'].'</a>';
			// $output .= '<a class="post_see_more" href="/question/'.$post['Post_URL'].'">See more</a>';
			$output .= '</div>';
			$output .= '</div>';
		}
	}
	return $output;
}
/* Week post listing functions */
function view_week($week,$count,$uid,$sort_type,$page=1) { //Return list of posts on front page
	global $db;
	$output = "";	
	$default = DEFAULT_AVATAR;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	sort($posts);
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['comments_count'] = count_comments($posts[$p]['Post_ID']);
	}
	sort($posts);
	usort($posts,$sort_type);
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<div class="posts">';
	for ($i = 0; $i < count($posts); $i++) {
		if ($posts[$i]['Post_ID']) {
			$pid = $posts[$i]['Post_ID'];
			$output .= view_post($pid,$uid);
			$output .= ($i != (count($posts)-1)) ? '<hr>': "";
		}
	}
	$output .= '</div>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#feeds").load("/triggers/paging_week.php",{week:'.$week.',count:'.$count.',page:page});
				}
				setInterval(function(){
					$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"week",week:'.$week.',page:'.$page.'});
				},1000*60*5);
				</script>';
	if (count($posts) == 0) {
		$output .= '<h3>There is no post for this week.</h3>';
	}
	return $output;
}
/* Week post listing functions */
function view_course_week($cid,$week,$count,$uid,$sort_type,$page=1) { //Return list of posts on front page
	global $db;
	$output = "";	
	$default = DEFAULT_AVATAR;
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	$posts = array_filter($posts, array(new Filter($cid), 'filter_cid'));
	$posts = array_filter($posts, array(new Filter($week), 'filter_week'));
	sort($posts);
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['comments_count'] = count_comments($posts[$p]['Post_ID']);
	}
	sort($posts);
	usort($posts,$sort_type);
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<div class="posts">';
	for ($i = 0; $i < count($posts); $i++) {
		if ($posts[$i]['Post_ID']) {
			$pid = $posts[$i]['Post_ID'];
			$output .= view_post($pid,$uid);
			$output .= ($i != (count($posts)-1)) ? '<hr>': "";
		}
	}
	$output .= '</div>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#feeds").load("/triggers/paging_course_week.php",{cid:'.$cid.',week:'.$week.',count:'.$count.',page:page});
				}
				setInterval(function(){
					$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"course_week",cid:'.$cid.',week:'.$week.',page:'.$page.'});
				},1000*60*5);
				</script>';
	if (count($posts) == 0) {
		$output .= '<h3>There is no post for this course and week.</h3>';
	}
	return $output;
}
/* Ask Question Function */
function ask_question($rid,$cid,$week) {
	$output = "";
	$feeds_type = "";
	if ($cid == 0 && $week == 0) {
		$feeds_type = 'front';
	} elseif ($cid != 0 && $week == 0) {
		$feeds_type = 'course';
	} elseif ($cid != 0 && $week != 0) {
		$feeds_type = 'course_week';
	} elseif ($cid == 0 && $week != 0) {
		$feeds_type = 'week';
	}
	$is_guest = false;
	$cids = cids_load_all();
	for ($i = 0; $i < count($cids); $i++) {
		$my_course[$i] = course_load($cids[$i]);
		if ($my_course[$i]['Course_For_Guest'] == 1) {
			$is_guest = true;
		}
	}
	$course = course_load($cid);
	$output .= '<div id="ask_question">';
	$output .= '<div id="ask_label">Ask Question</div>';
	$output .= '<div id="question_section" title="Ask question">';
	$output .= '<span id="question_label">Type a question..</span>';
	$output .= '<a id="question_close_button"></a>';
	$output .= '<div class="question_element"><label for="question_title">Subject: </label><input class="element_input" id="question_title" name="question_title" type="text" size="30" /></div>';
	$output .= '<input class="element_input" id="question_url" name="question_url" type="hidden" />';
	$output .= '<div class="question_element"><label for="question_body">Type a question.. </label><br/><textarea class="element_textarea" id="question_body" name="question_body" rows="1"></textarea></div><br/>';
	$output .= ($rid != 2 && $rid != 4) ? '<div class="question_element"><label for="question_answer">(Optional) Type an answer.. </label><br/><textarea class="element_textarea" id="question_answer" name="question_answer" rows="1"></textarea></div><br/>': '<input type="hidden" id="question_answer" value="" />';
	$output .= ($rid == 2 || $rid == 4) ? '<div class="question_element"><input id="question_hide" type="checkbox" name="hide" value="1" /><label for="question_hide">Hide your username from others</label></div>': '<input id="question_hide" type="hidden" name="hide" value="0" />';
	$output .= '<div id="question_bottom">';
	$output .= '<div class="question_element week"><label for="question_week">Week: </label>'.(($week == 0) ? select_week('question_week'): select_week('question_week',$week)).'</div>';
	$output .= ($cid == 0) ? '<div class="question_element course"><label for="question_cid">Course: </label>'.select_course('question_cid').'</div>': '<input type="hidden" id="question_cid" value="'.$cid.'" />';
	$output .= '<a class="button '.(($rid == 0) ? 'disabled': "").'" id="post_submit">Post</a>';
	$output .= '</div>';
	$output .= '</div>';
	$output .= '<div id="save_post"></div>';
	$output .= '<div id="follow_post"></div>';
	$output .= '<script>
				$("#question_section").click(function(){
					$.ajax({
						type: "POST",
						url: "/triggers/validate_ask.php",
						data: {
							"rid": '.$rid.',
							"cid": '.$cid.'
						},
						dataType: "text"
					}).done(function(data){
						if (!data.includes("not_loggedin") && !data.includes("not_belonged") && !data.includes("not_allowed") && !data.includes("is_admin") && !data.includes("not_enroled") && !data.includes("no_course") || (!data.includes("not_loggedin") && !data.includes("is_admin") && data.includes("guest_mode"))) {
							$("#question_label").text("");
							$("#question_section .question_element,#question_close_button,#question_bottom").css("display","block");
							$("#question_section").css("cursor","default").animate({height:"'.(($rid != 2 && $rid != 4) ? '140px': '125px').'"},240);
						} else if (data.includes("not_loggedin")) {
							openLogin();
						} else if (data.includes("not_belonged")) {
							openWrap("You do not belong to this course");
						} else if (data.includes("not_allowed")) {
							openWrap("The course(s) not allow posting");
						} else if (data.includes("no_course") && !data.includes("guest_mode")) {
							openWrap("You do not belong to any course");
						} else if (data.includes("is_admin")) {
							openWrap("Admin cannot post question");
						} else if (data.includes("not_enroled")) {
							openWrap("You are not enroled in any course. Please ask your lecturer to enrol you into a course");
						}
					});
				});
				$("#question_close_button").click(function(e){
					e.stopPropagation();
					$("#question_label").text("Type a question..");
					$("#question_section .question_element,#question_close_button,#question_bottom").css("display","none");
					$("#question_section").css("cursor","text").animate({height:"36px"},240);
					$(".element_input").val("");
					$(".element_textarea").val("");
					$("#question_hide").attr("checked",false);
				});
				$("#post_submit").click(function(e){
					e.stopPropagation();
					if (!$(this).hasClass("disabled")) {
						var errors = "";
						if ($("#question_title").val() == "") {
							errors += "<p>Question subject is empty.</p>";
						}
						if ($("#question_body").val() == "") {
							errors += "<p>Question body is empty.</p>";
						}
						if ($("#question_title").val() != "" && $("#question_url").val() != "" && $("#question_body").val() != "") {
							appendDate();
							$("#save_post").load("/triggers/post_create.php",{cid:$("#question_cid").val(),week:$("#question_week").val(),title:$("#question_title").val(),url:$("#question_url").val(),body:$("#question_body").val(),answer:$("#question_answer").val(),hide:$("#question_hide:checked").val()}, function(data){
								if (data == "URL_EXISTS") {
									openWrap("Duplicated subject.");
								} else if (data == "URL_AVAILABLE") {
									$("#question_label").text("Type a question..");
									$("#question_section .question_element,#question_close_button,#question_bottom").css("display","none");
									$("#question_section").css("cursor","text").animate({height:"36px"},240);
									$(".element_input").val("");
									$(".element_textarea").val("");
									$("#question_hide").attr("checked",false);
									$("#follow_post").load("/triggers/latest_post_follow.php",function(){
										$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"'.$feeds_type.'", cid:"'.$cid.'", week:"'.$week.'"},function(){
											openWrap("Post created");
										});
									});
								}
							});
						} else {
							openWrap(errors);
							$("#question_title").focus();
						}
					} else {
						openLogin();
					}
				});
				function updateField(){
					var title = $("input#question_title").val();
					var url = slugify(title, {
						lowercase: true,
						separator: "-",
					});
					$("input#question_url").val(url);
				}
				function appendDate() {
					var url = $("input#question_url").val();
					var d = new Date();
					var month = d.getMonth()+1;
					var day = d.getDate();
					var outputDate = d.getFullYear() + "-" + (month<10 ? "0" : "") + month + "-" + (day<10 ? "0" : "") + day;
					var url = url + "-" + outputDate;
					$("input#question_url").val(url);
				}
				$("input#question_title").keyup(updateField).keydown(updateField).change(updateField);
				$("textarea#question_body, textarea#question_answer").keyup(function(){
					limits($(this), 900);
				}).keydown(function(){
					limits($(this), 900);
				}).change(function(){
					limits($(this), 900);
				});
				$("input#question_title").keyup(function(){
					limits($(this), 42);
				}).keydown(function(){
					limits($(this), 42);
				}).change(function(){
					limits($(this), 42);
				});
				</script>';
	$output .= '</div>';
	return $output;
}
/* Comment Functions */
function list_comments($pid,$c=null) { //Return list of comments by post ID
	$output = "";
	$size = 30;
	$default = DEFAULT_AVATAR;
	$post = post_load($pid);
	$cid = $post['Course_ID'];
	$course = course_load($cid);
	$current_user = user_load($_SESSION['uid']);
	$current_grav_url = "https://0.gravatar.com/avatar/" . md5( strtolower( trim((string) $current_user['User_Mail'] ) ) ) . "?d=identicon&s=" . $size;
	$comments = comments_load_by_pid($pid);
	usort($comments,'sort_comment_date_ascend');
	$count = ($c == null) ? count($comments): $c;
	$output .= '<div class="comments'.'" id="comments_pid_'.$pid.'"><!-- Start comments of post '.$pid.' -->';
	for ($i = 0; $i < $count; $i++) {
		if (isset($comments[$i])) {
			$user = user_load($comments[$i]['User_ID']);
			$comment_vote = comment_vote_load($comments[$i]['Comment_ID'],$_SESSION['uid']);
			$comid = $comments[$i]['Comment_ID'];
			$hide_label = ($comments[$i]['Comment_Hide_Name'] == 0 && user_existed($comments[$i]['User_ID'])) ? 'Hide': 'Unhide';
			$email = $user['User_Mail'];
			$grav_url = ($comments[$i]['Comment_Hide_Name'] == 0 && user_existed($comments[$i]['User_ID'])) ? "https://0.gravatar.com/avatar/" . md5( strtolower( trim((string) $email ) ) ) . "?d=identicon&s=" . $size: $default;
			$output .= '<div class="comment">';
			$output .= '<a class="author" href="'.(($comments[$i]['Comment_Hide_Name'] == 1 || !user_existed($comments[$i]['User_ID'])) ? "#": '/user/'.$user['User_Username']).'"><img alt="'.$user['User_Username'].'" src="'.$grav_url.'" width="30px"/></a>';
			$output .= '<div class="comment_right_detail">';
			$output .= '<div class="name'.(($user['Role_ID'] == 3) ? ' lecturer': "").'">'.(($comments[$i]['Comment_Hide_Name'] == 0 && user_existed($comments[$i]['User_ID'])) ? ((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username']): 'Anonymous').'</div>';
			$output .= '<div class="date">'.ago($comments[$i]['Comment_Created']).(($comments[$i]['Comment_Edited'] != 0) ? ' - edited: '.ago($comments[$i]['Comment_Edited']): "").'</div>';
			$output .= '<p>'.htmlspecialchars_decode($comments[$i]['Comment_Body']).'</p>';
			$output .= '<a title="'.(($comment_vote['CommentVote_Like'] == 0) ? 'Like': 'Unlike').' this comment" class="button'.(($comment_vote['CommentVote_Like'] == 0) ? ' like': ' like clicked').'" id="comment_like_comid_'.$comid.'">'.count_comment_likes($comid).' Like'.((count_comment_likes($comid) == 0 || count_comment_likes($comid) == 1) ? "": 's').'</a>';
			$output .= '<div id="save_comment_like_comid_'.$comid.'"></div>';
			$output .= '<a title="'.(($comment_vote['CommentVote_Dislike'] == 0) ? 'Dislike': 'Undislike').' this comment" class="button'.(($comment_vote['CommentVote_Dislike'] == 0) ? ' dislike': ' dislike clicked').'" id="comment_dislike_comid_'.$comid.'">'.count_comment_dislikes($comid).' Dislike'.((count_comment_dislikes($comid) == 0 || count_comment_dislikes($comid) == 1) ? "": 's').'</a>';
			$output .= '<div id="save_comment_dislike_comid_'.$comid.'"></div>';
			$output .= (isset($_SESSION['uid']) && $_SESSION['uid'] == $comments[$i]['User_ID'] || ($_SESSION['rid'] == 3 && course_belonged($cid,$_SESSION['uid']))) ? '<div id="comment_comid_'.$comments[$i]['Comment_ID'].'"><textarea id="textarea_body_comment_edit_comid_'.$comid.'" name="body">'.strip_tags(htmlspecialchars_decode($comments[$i]['Comment_Body'])).'</textarea><a class="button" id="submit_comment_edit_comid_'.$comid.'">Submit</a></div><div id="save_comment_edit_comid_'.$comid.'"></div><a title="Edit this comment" class="button edit_comment" onclick="toggle_comment_edit('."'".'comment_comid_'.$comid."'".',this)">Edit</a><a title="Delete this comment" class="button delete_comment" id="submit_comment_delete_comid_'.$comid.'">Delete</a><div id="save_comment_delete_comid_'.$comid.'"></div>': "";
			$output .= '</div>';
			$output .= '</div>';
			$output .= '<script>
						$("#comment_like_comid_'.$comid.'").click(function(){
							commentLike('.$comid.','.$pid.');
						});
						$("#comment_dislike_comid_'.$comid.'").click(function(){
							commentDislike('.$comid.','.$pid.');
						});
						$("#submit_comment_edit_comid_'.$comid.'").click(function(){
							commentEdit('.$comid.','.$pid.');
						});
						$("#submit_comment_delete_comid_'.$comid.'").click(function(){
							commentDeleteDialog('.$comid.','.$pid.');
						});
						$("textarea#textarea_body_comment_edit_comid_'.$comid.'").keyup(function(){
							limits($(this), 420);
						}).keydown(function(){
							limits($(this), 420);
						}).change(function(){
							limits($(this), 420);
						});
						</script>
						';
		}
	}
	$output .= '<div id="addCommentContainer">';
	$output .= '<a class="author" href="/user/'.$current_user['User_Username'].'"><img src="'.$current_grav_url.'" width="30px"/></a>';
	$output .= '<textarea placeholder="Leave a comment..." name="body" id="textarea_body_comment_create_pid_'.$pid.'" cols="20" rows="5"></textarea>';
	$output .= '<input id="input_hide_comment_create_pid_'.$pid.'" type="checkbox" name="hide" value="1" /><label for="input_hide_comment_create_pid_'.$pid.'">Hide your username from others</label><br/>';
	$output .= '<input id="input_uid_comment_create_pid_'.$pid.'" type="hidden" value="'.$_SESSION['uid'].'" />';
	$output .= '<a class="button" id="submit_comment_create_pid_'.$pid.'">Create comment</a>';
	$output .= '</div>';
	$output .= '<div id="save_comment_create_pid_'.$pid.'"></div>';
	$output .= '<script>
				$("#submit_comment_create_pid_'.$pid.'").click(function(){
					commentCreate('.$pid.');
				}).keypress(function(e) {
					if(e.which == 13) {
						commentCreate('.$pid.');
					}
				});
				$("textarea#textarea_body_comment_create_pid_'.$pid.'").keyup(function(){
					limits($(this), 420);
				}).keydown(function(){
					limits($(this), 420);
				}).change(function(){
					limits($(this), 420);
				});
				</script>
				';
	$output .= '</div>';
	return $output;
}
function comments_update($pid) { //Updated comments list on AJAX call
	$start_pos = strpos(list_comments($pid),'<!-- Start comments of post '.$pid.' -->');
	return substr(list_comments($pid),$start_pos,-6);
}
function list_comments_without_right($pid,$c=null) { //Return list of comments by post ID without normal rights 
	$output = "";
	$default = DEFAULT_AVATAR;
	$post = post_load($pid);
	$comments = comments_load_by_pid($pid);
	usort($comments,'sort_comment_date_ascend');
	$count = ($c == null) ? count($comments): $c;
	$output .= '<div class="comments" id="comments_pid_'.$pid.'">';
	for ($i = 0; $i < $count; $i++) {
		if (isset($comments[$i])) {
			$user = user_load($comments[$i]['User_ID']);
			$comid = $comments[$i]['Comment_ID'];
			$comment_vote = comment_vote_load($comid,$_SESSION['uid']);
			$email = $user['User_Mail'];
			$size = 30;
			$grav_url = ($comments[$i]['Comment_Hide_Name'] == 0 && user_existed($comments[$i]['User_ID'])) ? "https://0.gravatar.com/avatar/" . md5( strtolower( trim((string) $email ) ) ) . "?d=identicon&s=" . $size: $default;
			$output .= '<div class="comment">';
			$output .= '<a class="author" href="/user/'.$user['User_Username'].'"><img alt="'.$user['User_Username'].'" src="'.$grav_url.'" width="30px"/></a>';
			$output .= '<div class="comment_right_detail">';
			$output .= '<div class="name'.(($user['Role_ID'] == 3) ? ' lecturer': "").'">'.(($comments[$i]['Comment_Hide_Name'] == 0 && user_existed($comments[$i]['User_ID'])) ? ((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username']): 'Anonymous').'</div>';
			$output .= '<div class="date">'.ago($comments[$i]['Comment_Created']).(($comments[$i]['Comment_Edited'] != 0) ? ' - edited: '.ago($comments[$i]['Comment_Edited']): "").'</div>';
			$output .= '<p>'.htmlspecialchars_decode($comments[$i]['Comment_Body']).'</p>';
			$output .= '<a title="Like this comment" class="button disabled like" id="comment_like_comid_'.$comid.'">'.count_comment_likes($comid).' Like'.((count_comment_likes($comid) == 0 || count_comment_likes($comid) == 1) ? "": 's').'</a>';
			$output .= '<a title="Dislike this comment" class="button disabled dislike" id="comment_dislike_comid_'.$comid.'">'.count_comment_dislikes($comid).' Dislike'.((count_comment_dislikes($comid) == 0 || count_comment_dislikes($comid) == 1) ? "": 's').'</a>';
			$output .= '</div>';
			$output .= '</div>';
		}
	}
	$output .= '</div>';
	return $output;
}
function create_comment($pid,$uid,$body,$hide) { //Create new comment
	global $db;
	$body = strip_tags($body);
	$body = convert_link($body);
	$array = array(
				'Post_ID' => $pid,
				'User_ID' => $uid,
				'Comment_Body' => $body,
				'Comment_Hide_Name' => $hide,
				'Comment_Created' => time()
			);
	$db->insert_record($array,'COMMENT');
}
function edit_comment($comid,$body) { //Edit comment details including comment body and updated time
	global $db;
	$body = strip_tags($body);
	$body = convert_link($body);
	$array = array(
				'Comment_Body' => $body,
				'Comment_Edited' => time()
			);
	$db->update_record($array,'Comment_ID',$comid,'COMMENT');
}
function delete_comment($comid) { //Delete comment
	global $db;
	$db->delete_record('Comment_ID',$comid,'COMMENT');
	delete_comment_vote_from_comid($comid);
}
function delete_comments_from_pid($pid) { //Delete comments belonged to a post
	$comments = comments_load_by_pid($pid);
	sort($comments);
	foreach ($comments as $comment) {
		delete_comment($comment['Comment_ID']);
	}
}
function delete_comment_vote_from_comid($comid) { //Delete all likes and dislikes of comment
	global $db;
	$db->delete_record('Comment_ID',$comid,'COMMENT_VOTE');
}
/* Sort Functions */
function sort_descend($a,$b){ //Call back function to sort descendently
	if (isset($a['sort']) && isset($b['sort'])) {
		if ((int)$a['sort'] == (int)$b['sort']) {
			return 0;
		}
		return ((int)$b['sort'] < (int)$a['sort']) ? -1 : 1;
	}
}
function sort_ascend($a,$b){ //Call back function to sort ascendently
	if (isset($a['sort']) && isset($b['sort'])) {
		if ((int)$a['sort'] == (int)$b['sort']) {
			return 0;
		}
		return ((int)$a['sort'] < (int)$b['sort']) ? -1 : 1;
	}
}
function sort_user_descend($a,$b){ //Call back function to sort descendently
	if (isset($a['User_ID']) && isset($b['User_ID'])) {
		if ((int)$a['User_ID'] == (int)$b['User_ID']) {
			return 0;
		}
		return ((int)$b['User_ID'] < (int)$a['User_ID']) ? -1 : 1;
	}
}
function sort_user_ascend($a,$b){ //Call back function to sort ascendently
	if (isset($a['User_ID']) && isset($b['User_ID'])) {
		if ((int)$a['User_ID'] == (int)$b['User_ID']) {
			return 0;
		}
		return ((int)$a['User_ID'] < (int)$b['User_ID']) ? -1 : 1;
	}
}
function sort_comments_count_descend($a,$b){ //Call back function to sort by comments descendently
	if (isset($a['comments_count']) && isset($b['comments_count'])) {
		if ((int)$a['comments_count'] == (int)$b['comments_count']) {
			return 0;
		}
		return ((int)$b['comments_count'] < (int)$a['comments_count']) ? -1 : 1;
	}
}
function sort_comments_count_ascend($a,$b){ //Call back function to sort by comments ascendently
	if (isset($a['comments_count']) && isset($b['comments_count'])) {
		if ((int)$a['comments_count'] == (int)$b['comments_count']) {
			return 0;
		}
		return ((int)$a['comments_count'] < (int)$b['comments_count']) ? -1 : 1;
	}
}
function sort_follows_count_descend($a,$b){ //Call back function to sort by follows descendently
	if (isset($a['follows_count']) && isset($b['follows_count'])) {
		if ((int)$a['follows_count'] == (int)$b['follows_count']) {
			return 0;
		}
		return ((int)$b['follows_count'] < (int)$a['follows_count']) ? -1 : 1;
	}
}
function sort_follows_count_ascend($a,$b){ //Call back function to sort by follows ascendently
	if (isset($a['follows_count']) && isset($b['follows_count'])) {
		if ((int)$a['follows_count'] == (int)$b['follows_count']) {
			return 0;
		}
		return ((int)$a['follows_count'] < (int)$b['follows_count']) ? -1 : 1;
	}
}
function sort_likes_count_descend($a,$b){ //Call back function to sort by likes descendently
	if (isset($a['likes_count']) && isset($b['likes_count'])) {
		if ((int)$a['likes_count'] == (int)$b['likes_count']) {
			return 0;
		}
		return ((int)$b['likes_count'] < (int)$a['likes_count']) ? -1 : 1;
	}
}
function sort_likes_count_ascend($a,$b){ //Call back function to sort by likes ascendently
	if (isset($a['likes_count']) && isset($b['likes_count'])) {
		if ((int)$a['likes_count'] == (int)$b['likes_count']) {
			return 0;
		}
		return ((int)$a['likes_count'] < (int)$b['likes_count']) ? -1 : 1;
	}
}
function sort_average_rates_descend($a,$b){ //Call back function to sort average_rates descendently
	if (isset($a['average_rates']) && isset($b['average_rates'])) {
		if ((int)$a['average_rates'] == (int)$b['average_rates']) {
			return 0;
		}
		return ((int)$b['average_rates'] < (int)$a['average_rates']) ? -1 : 1;
	}
}
function sort_average_rates_ascend($a,$b){ //Call back function to sort average_rates ascendently
	if (isset($a['average_rates']) && isset($b['average_rates'])) {
		if ((int)$a['average_rates'] == (int)$b['average_rates']) {
			return 0;
		}
		return ((int)$a['average_rates'] < (int)$b['average_rates']) ? -1 : 1;
	}
}
function sort_post_date_descend($a,$b){ //Call back function to sort date descendently
	if (isset($a['Post_Created']) && isset($b['Post_Created'])) {
		return strcmp($b['Post_Created'],$a['Post_Created']);
	}
}
function sort_post_date_ascend($a,$b){ //Call back function to sort date ascendently
	if (isset($a['Post_Created']) && isset($b['Post_Created'])) {
		return strcmp($a['Post_Created'],$b['Post_Created']);
	}
}
function sort_semester_start_date_descend($a,$b){ //Call back function to sort date descendently
	if (isset($a['Semester_Start_Date']) && isset($b['Semester_Start_Date'])) {
		return strcmp($b['Semester_Start_Date'],$a['Semester_Start_Date']);
	}
}
function sort_semester_start_date_ascend($a,$b){ //Call back function to sort date ascendently
	if (isset($a['Semester_Start_Date']) && isset($b['Semester_Start_Date'])) {
		return strcmp($a['Semester_Start_Date'],$b['Semester_Start_Date']);
	}
}
function sort_comment_date_descend($a,$b){ //Call back function to sort date descendently
	if (isset($a['Comment_Created']) && isset($b['Comment_Created'])) {
		return strcmp($b['Comment_Created'],$a['Comment_Created']);
	}
}
function sort_comment_date_ascend($a,$b){ //Call back function to sort date ascendently
	if (isset($a['Comment_Created']) && isset($b['Comment_Created'])) {
		return strcmp($a['Comment_Created'],$b['Comment_Created']);
	}
}
function sort_course_code_descend($a,$b){ //Call back function to sort course code descendently
	if (isset($a['Course_Code']) && isset($b['Course_Code'])) {
		return strcmp($b['Course_Code'],$a['Course_Code']);
	}
}
function sort_course_code_ascend($a,$b){ //Call back function to sort course code ascendently
	if (isset($a['Course_Code']) && isset($b['Course_Code'])) {
		return strcmp($a['Course_Code'],$b['Course_Code']);
	}
}
/* Filter Class */
class Filter {
	private $num;
	public function __construct($num) {
		$this->num = $num;
	}
	public function filter_cid($a) { //Call back function to filter array by course ID
		if (isset($a['Course_ID'])) {
			if ($a['Course_ID'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_course_belonged($a) { //Call back function to filter array by course belonged attribute
		if (isset($a['Course_ID'])) {
			if (course_belonged($a['Course_ID'],$_SESSION['uid']) == $this->num) {
				return true;
			}
		}
	}
	public function filter_rid($a) { //Call back function to filter array by role ID
		if (isset($a['Role_ID'])) {
			if ($a['Role_ID'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_current($a) { //Call back function to filter array by current boolean
		if (isset($a['Post_Current'])) {
			if ($a['Post_Current'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_type($a) { //Call back function to filter array by type
		if (isset($a['type'])) {
			if ($a['type'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_week($a) { //Call back function to filter array by type
		if (isset($a['Post_Week'])) {
			if ($a['Post_Week'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_post_hide($a) { //Call back function to filter array by type
		if (isset($a['Post_Hide_Name'])) {
			if ($a['Post_Hide_Name'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_course_allowed($a) { //Call back function to filter array by type
		if (isset($a['Course_Allowed'])) {
			if ($a['Course_Allowed'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_post_rate($a) { //Call back function to filter array by post rate
		if (isset($a['PostRate'])) {
			if ($a['PostRate'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_post_like($a) { //Call back function to filter array by post like
		if (isset($a['PostVote_Like'])) {
			if ($a['PostVote_Like'] == $this->num) {
				return true;
			}
		}
	}
	public function filter_post_dislike($a) { //Call back function to filter array by post dislike
		if (isset($a['PostVote_Dislike'])) {
			if ($a['PostVote_Dislike'] == $this->num) {
				return true;
			}
		}
	}
}
//array_filter($posts, array(new Filter($cid), 'filter_cid'))
/* Search Functions */
function search_question($query,$cid,$count,$page=1) { //Search questions by post title and post body
	global $db;
	$output = "";
	$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
	$posts = array();
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2) {
		$sql = "SELECT * FROM " . PREFIX . "POST WHERE (Post_Title LIKE :query OR Post_Question LIKE :query OR Post_Answer LIKE :query OR Post_URL LIKE :query)";
	} elseif (!isset($_SESSION['rid']) || $_SESSION['rid'] == 2) {
		$sql = "SELECT * FROM " . PREFIX . "POST WHERE Post_Current=1 AND (Post_Title LIKE :query OR Post_Question LIKE :query OR Post_Answer LIKE :query OR Post_URL LIKE :query)";
	}
	$stmt = $db->link->prepare($sql);
	$queryParam = '%' . $query . '%';
	$stmt->bindParam(':query', $queryParam, PDO::PARAM_STR);
	$stmt->execute();
	$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if ($cid != 0) {
		$posts = array_filter($posts, array(new Filter($cid), 'filter_cid'));
	}
	usort($posts,'sort_post_date_descend');
	$pagination = new pagination($posts,$page,$count,5);
	$pagination->setShowFirstAndLast(true);
	$pagination->setMainSeperator("");
	$posts = $pagination->getResults();
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<div class="posts">';
	for ($i = 0; $i < count($posts); $i++) {
		if (isset($posts[$i])) {
			$pid = $posts[$i]['Post_ID'];
			$output .= view_post($pid,$uid);
			$output .= ($i != (count($posts)-1)) ? '<hr>': "";
		}
	}
	$output .= '</div>';
	$output .= '<div class="paging">'.$pagination->getLinks().'</div>';
	$output .= '<script>
				function turnPage(page) {
					$("#feeds").load("/triggers/paging_search.php",{count:'.$count.',cid:'.$cid.',page:page,keyword:"'.$query.'"});
				}
				</script>';
	if (count($posts) == 0) {
		$output .= '<h3>There is no results for this search criteria.</h3>';
	}
	return $output;
}
// get current URL
function currentURL() { //Get current page URL
	$page_url = 'http';
	if (isset($_SERVER['HTTPS'])){
		if ($_SERVER['HTTPS'] == 'on') {$page_url .= 's';}
	}
	$page_url .= '://';
	if ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') {
		$page_url .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
	} else {
		$page_url .= $_SERVER['SERVER_NAME'];
	}
	return $page_url;
}
function baseURL() {
	return currentURL().$_SERVER['REQUEST_URI'];
}
/* Menu Functions */
function style_active_course_menu() { //Return the style of active course menu list
	global $db;
	$output = "";
	$courses = $db->array_load_all('COURSE');
	$output .= '<style>';
	for ($i = 0; $i < count($courses); $i++) {
		$output .= 'body.cid-'.$courses[$i]['Course_ID'].' #leftmenu > ul > li > a.cid-'.$courses[$i]['Course_ID'];
		$output .= ($i != count($courses)-1) ? ',': "";
	}
	$output .= ' {text-decoration: underline}';
	$output .= '</style>';
	return $output;
}
function style_active_week_menu($cid=null) {
	$output = "";
	$weeks = array('1','2','3','4','5','6','7','8','9','10','11','12');
	$output .= '<style>';
	for ($i = 0; $i < count($weeks); $i++) {
		$output .= 'body.week-'.$weeks[$i].(($cid != null) ? '.cid-'.$cid: "").' #weeks ul > li > a.week-'.$weeks[$i].(($cid != null) ? '.cid-'.$cid: "");
		$output .= ($i != count($weeks)-1) ? ',': "";
	}
	$output .= ' 	{background: #404041;
					color: white;
					-webkit-border-radius: 7px;
					-webkit-border-radius: 7px;
					-moz-border-radius: 7px;
					-moz-border-radius: 7px;
					border-radius: 7px;
					border-radius: 7px;
					}';
	$output .= '</style>';
	return $output;
}
/* Ago Function Source: http://drupal.org/node/61565 */
function ago($time)
{
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");
	$now = time();
	$difference = $now - $time;
	$tense = "ago";
	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
	}
	$difference = round($difference);
	if($difference != 1) {
       $periods[$j].= "s";
	}
	return "$difference $periods[$j] ago ";
}
/* Title to URL Function Source: http://papermashup.com/create-a-url-from-a-string-of-text-with-php/ */
function create_slug($string){
   $string = preg_replace( '/[«»""!?,.!@£$%^&*{};:()]+/', "", $string );
   $string = strtolower($string);
   $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
   return $slug;
}
/* Weeks Bar Function */
function weeks_bar($cid=null) {
	$output = "";
	if ($cid != null) {
		$course = course_load($cid);
	}
	$weeks = array('1','2','3','4','5','6','7','8','9','10','11','12');
	$output .= '<div id="weeks_toggle">Choose a week</div>';
	$output .= '<ul id="weeks_bar">';
	for ($i = 0; $i < count($weeks); $i++) {
		$output .= '<li class="week week-'.$weeks[$i].(($cid != null) ? ' cid-'.$cid: "").'"><a class="week week-'.$weeks[$i].(($cid != null) ? ' cid-'.$cid: "").'" href="/'.(($cid != null) ? 'course/'.$course['Course_Code'].'/': "").'week/'.$weeks[$i].'">'.$weeks[$i].'</a></li>';
	}
	$output .= '</ul>';
	$output .= '<script>
				$("#weeks_toggle").click(function(){
					if ($("ul#weeks_bar").css("display") == "none") {
						$(this).addClass("clicked");
						$("#weeks").css("width","340px");
						$("ul#weeks_bar").css("display","block").animate({marginLeft:"98px"},200);
					} else if ($("ul#weeks_bar").css("display") == "block") {
						$(this).removeClass("clicked");
						$("ul#weeks_bar").css("display","none").animate({marginLeft:"0px"},200);
						$("#weeks").css("width","140px");
					}
				});
				</script>';
	return $output;
}
/* Get Post Week Function */
function get_post_week($timestamp) {
	$current_semester = current_semester_load();
	$day_diff = day_diff($current_semester['Semester_Start_Date'],$timestamp);
	return number_format(ceil($day_diff/7),0);
}
function day_diff($start,$end) {
	$diff = $end - $start;
	return round($diff/86400);
}
/* Report Function */
function select_report_type($name,$type=null) { //Return select element of report types
	$output = "";
	$types = array('Number of questions per week','Number of questions per course','Most popular questions','Most difficult questions');
	$output .= '<select id="'.$name.'" name="'.$name.'">';
	for ($i = 0; $i < count($types); $i++) {
		$selected = ($type != null && $type == $types[$i]) ? 'selected': "";
		$output .= '<option '.$selected.' value="'.$types[$i].'">'.$types[$i].'</option>';
	}
	$output .= '</select>';
	return $output;
}
function report_most_difficult($count,$cid=0) {
	global $db;
	$output = "";
	$course = course_load($cid);
	$output .= '<div id="report_most_difficult">';
	$output .= select_course('cid',$cid);
	$output .= '<h2>Most difficult questions of '.(($cid == 0) ? 'all courses': '"'.$course['Course_Name'].'"').'</h2>';
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	if ($cid != 0) {
		$posts = array_filter($posts, array(new Filter($cid), 'filter_cid'));
	}
	sort($posts);
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['average_rates'] = average_post_rates_with_decimal($posts[$p]['Post_ID'],1);
	}
	usort($posts,'sort_average_rates_descend');
	$output .= '<table width="50%">';
	$output .= '<tr><th>Most difficult questions</th><th>Average rate</th></tr>';
	for ($i = 0; $i < $count; $i++) {
		if (isset($posts[$i])) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'><td width="30%"><a href="/question/'.$posts[$i]['Post_URL'].'">'.$posts[$i]['Post_Title'].'</td><td width="20%">'.$posts[$i]['average_rates'].'</td></tr>';
		}
	}
	$output .= '</table><br/>';
	$output .= '</div>';
	$output .= '<script>
				$("select#cid").change(function(){
					$("#report_most_difficult").load("/triggers/report_most_difficult.php",{cid:$(this).val(),report_type:"Most difficult questions",count:'.$count.'});
				});
				</script>';
	return $output;
}
function report_most_popular($count,$cid=0) {
	global $db;
	$output = "";
	$course = course_load($cid);
	$output .= '<div id="report_most_popular">';
	$output .= select_course('cid',$cid);
	$output .= '<h2>Most popular questions of '.(($cid == 0) ? 'all courses': '"'.$course['Course_Name'].'"').'</h2>';
	$posts = $db->array_load_all('POST');
	$posts = array_filter($posts, array(new Filter(1), 'filter_current'));
	if ($cid != 0) {
		$posts = array_filter($posts, array(new Filter($cid), 'filter_cid'));
	}
	sort($posts);
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['comments_count'] = count_comments($posts[$p]['Post_ID']);
	}
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['likes_count'] = count_post_likes($posts[$p]['Post_ID']);
	}
	for ($p = 0; $p < count($posts); $p++) {
		$posts[$p]['follows_count'] = count_post_follows($posts[$p]['Post_ID']);
	}
	usort($posts,'sort_likes_count_descend');
	$output .= '<table width="50%">';
	$output .= '<tr><th>Most liked questions</th><th>Number of likes</th></tr>';
	for ($i = 0; $i < $count; $i++) {
		if (isset($posts[$i])) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'><td width="30%"><a href="/question/'.$posts[$i]['Post_URL'].'">'.$posts[$i]['Post_Title'].'</td><td width="20%">'.$posts[$i]['likes_count'].(($posts[$i]['likes_count'] != 0 && $posts[$i]['likes_count'] != 1) ? ' likes': ' like').'</td></tr>';
		}
	}
	$output .= '</table><br/>';
	usort($posts,'sort_comments_count_descend');
	$output .= '<table width="50%">';
	$output .= '<tr><th>Most commented questions</th><th>Number of comments</th></tr>';
	for ($i = 0; $i < $count; $i++) {
		if (isset($posts[$i])) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'><td width="30%"><a href="/question/'.$posts[$i]['Post_URL'].'">'.$posts[$i]['Post_Title'].'</td><td width="20%">'.$posts[$i]['comments_count'].(($posts[$i]['comments_count'] != 0 && $posts[$i]['comments_count'] != 1) ? ' comments': ' comment').'</td></tr>';
		}
	}
	$output .= '</table><br/>';
	usort($posts,'sort_follows_count_descend');
	$output .= '<table width="50%">';
	$output .= '<tr><th>Most followed questions</th><th>Number of follows</th></tr>';
	for ($i = 0; $i < $count; $i++) {
		if (isset($posts[$i])) {
			$class = 'class="'.table_row_class($i).'"';
			$output .= '<tr '.$class.'><td width="30%"><a href="/question/'.$posts[$i]['Post_URL'].'">'.$posts[$i]['Post_Title'].'</td><td width="20%">'.$posts[$i]['follows_count'].(($posts[$i]['follows_count'] != 0 && $posts[$i]['follows_count'] != 1) ? ' follows': ' follow').'</tr></td>';
		}
	}
	$output .= '</table><br/>';
	$output .= '</div>';
	$output .= '<script>
				$("select#cid").change(function(){
					$("#report_most_popular").load("/triggers/report_most_popular.php",{cid:$(this).val(),report_type:"Most popular questions",count:'.$count.'});
				});
				</script>';
	return $output;
}
function chart_questions_per_course() {
	$output = "";
	$courses = courses_load_from_uid($_SESSION['uid']);
	$output .= '<h2>Number of questions and their figures in your courses</h2><div id="mychart"></div>';
	$output .= '<script>
				(function() {
					YUI().use("charts-legend", function (Y)
					{
						var myDataValues = [
			   ';
	for ($i = 0; $i < count($courses); $i++) {
		$output .= '
							{course: "'.$courses[$i]['Course_Code'].'", "Questions": '.count_posts_from_cid($courses[$i]['Course_ID']).', "Likes": '.count_post_likes_from_cid($courses[$i]['Course_ID']).', "Dislikes": '.count_post_dislikes_from_cid($courses[$i]['Course_ID']).', "Follows": '.count_post_follows_from_cid($courses[$i]['Course_ID']).', "Comments": '.count_comments_from_cid($courses[$i]['Course_ID']).(($i != (count($courses)-1)) ? '},': '}');
	}
	$output .= '		];
						var myAxes = {
							values:{
								position:"left",
								type:"numeric",
								maximum: "NumericAxis",
								minimum: 0,
								roundingMethod: "niceNumber",
								alwaysShowZero:true
							}
						};
						var styleDef = {
							axes: { 
								course: { 
									label: {
										rotation: -90
									}
								}
							}
						};
						var mychart = new Y.Chart({
						legend: {
							position: "right",
							width: 350,
							height: 350,
							styles: {
								hAlign: "center",
								hSpacing: 4
							}
						},
						dataProvider:myDataValues, 
						render:"#mychart",
						type: "column",
						axes:myAxes,
						styles: styleDef,
						interactionType:"marker",
						seriesKeys:["Questions","Likes","Dislikes","Follows","Comments"],
                        horizontalGridlines: true,
                        verticalGridlines: true,
						categoryKey:"course",
						tooltip: {
							styles: { 
								backgroundColor: "#414042",
								color: "#fff",
								borderColor: "#fff",
								textAlign: "center"
							}
						}
						});
					});
				})();
				</script>';
	return $output;
}
function chart_questions_per_week($cid=0) {
	$output = "";
	$course = course_load($cid);
	$weeks = array('1','2','3','4','5','6','7','8','9','10','11','12');
	$output .= '<div id="chart_course_week">';
	$output .= select_course('cid',$cid);
	$output .= '<h2>Number of questions per week of '.(($cid == 0) ? 'all courses': '"'.$course['Course_Name'].'"').'</h2>';
	$output .= '<div id="mychart"></div>';
	$output .= '</div>';
	$output .= '<script>
				(function() {
					YUI().use("charts-legend", function (Y)
					{
						var myDataValues = [
			   ';
	for ($i = 0; $i < count($weeks); $i++) {
		$output .= '
							{week: "Week '.$weeks[$i].'", "Questions": '.(($cid != 0) ? count_posts_by_course_week($cid,$weeks[$i]): count_posts_by_week($weeks[$i])).', "Likes": '.(($cid != 0) ? count_post_likes_by_course_week($cid,$weeks[$i]): count_post_likes_by_week($weeks[$i])).', "Dislikes": '.(($cid != 0) ? count_post_dislikes_by_course_week($cid,$weeks[$i]): count_post_dislikes_by_week($weeks[$i])).', "Follows": '.(($cid != 0) ? count_post_follows_by_course_week($cid,$weeks[$i]): count_post_follows_by_week($weeks[$i])).', "Comments": '.(($cid != 0) ? count_comments_by_course_week($cid,$weeks[$i]): count_comments_by_week($weeks[$i])).(($i != (count($weeks)-1)) ? '},': '}');
	}
	$output .= '		];
						var myAxes = {
							values:{
								position:"left",
								type:"numeric",
								maximum: "NumericAxis",
								minimum: 0,
								roundingMethod: "niceNumber",
								alwaysShowZero:true
							}
						};
						var mychart = new Y.Chart({
						legend: {
							position: "right",
							width: 350,
							height: 350,
							styles: {
								hAlign: "center",
								hSpacing: 4
							}
						},
						dataProvider:myDataValues, 
						render:"#mychart",
						type: "column",
						axes:myAxes,
						interactionType:"marker",
						horizontalGridlines: {
							styles: {
								line: {
									color: "#dad8c9"
								}
							}
						},
						verticalGridlines: {
							styles: {
								line: {
									color: "#dad8c9"
								}
							}
						},
						//styles: styleDef,
						categoryKey:"week",
						tooltip: {
							styles: { 
								backgroundColor: "#414042",
								color: "#fff",
								borderColor: "#fff",
								textAlign: "center"
							}
						}
						});
					});
				})();
				</script>';
	$output .= '<script>
				$("select#cid").change(function(){
					$("#chart_course_week").load("/triggers/chart_course_week.php",{cid:$(this).val(),report_type:"Number of questions per week"});
				});
				</script>';
	return $output;
}
function count_interactions($pid) {
	$count = count_post_likes($pid) + count_post_dislikes($pid) + count_post_follows($pid) + count_comments($pid);
	return $count;
}
/**
 * Convert a comma separated file into an associated array.
 * The first row should contain the array keys.
 * 
 * Example:
 * 
 * @param string $filename Path to the CSV file
 * @param string $delimiter The separator used in the file
 * @return array
 * @link http://gist.github.com/385876
 * @author Jay Williams <http://myd3.com/>
 * @copyright Copyright (c) 2010, Jay Williams
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
function csv_to_array($filename="", $delimiter=',')
{
	if(!file_exists($filename) || !is_readable($filename))
		return FALSE;
	$formattedArr = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
		{
			$formattedArr = $data;
		}
		fclose($handle);
	}
	return $formattedArr;
}
/* Excel file reader */
function parse_excel_to_table($filename) {
	$output = "";
	$object_PHPExcel = PHPExcel_IOFactory::load($filename);
	foreach ($object_PHPExcel->getWorksheetIterator() as $worksheet) {
		$worksheet_title = $worksheet->getTitle();
		$highest_row = $worksheet->getHighestRow();
		$highest_column = $worksheet->getHighestColumn();
		$highest_column_index = PHPExcel_Cell::columnIndexFromString($highest_column);
		$no_columns = ord($highest_column) - 64;
		$output .= '<br/>The worksheet <b>'.$worksheet_title.'</b> has ';
		$output .= $no_columns.' column'.(($no_columns > 1) ? 's': "").'(A-'.$highest_column.')';
		$output .= ' and '.$highest_row.' row'.(($highest_row > 1) ? 's': "");
		$output .= '<br/>Data: <table border="1">';
		for ($row = 1; $row <= $highest_row; $row++) {
			$output .= '<tr>';
			for ($col = 0; $col < $highest_column_index; $col++) {
				$cell = $worksheet->getCellByColumnAndRow($col,$row);
				$val = $cell->getValue();
				$output .= '<td>'.$val.'</td>';
			}
			$output .= '</tr>';
		}
		$output .= '</table>';
	}
	return $output;
}
function parse_excel_to_array($filename) {
	$worksheets = array();
	$object_PHPExcel = PHPExcel_IOFactory::load($filename);
	$i = 0;
	foreach ($object_PHPExcel->getWorksheetIterator() as $worksheet) {
		$worksheets[$i] = $worksheet->toArray();
		$i++;
	}
	return $worksheets;
}
function parse_excel_to_custom_array($filename) {
	$worksheets = array();
	$object_PHPExcel = PHPExcel_IOFactory::load($filename);
	$i = 0;
	foreach ($object_PHPExcel->getWorksheetIterator() as $worksheet) {
		$worksheet_title = $worksheet->getTitle();
		$highest_row = $worksheet->getHighestRow();
		$highest_column = $worksheet->getHighestColumn();
		$highest_column_index = PHPExcel_Cell::columnIndexFromString($highest_column);
		$no_columns = ord($highest_column) - 64;
		$worksheets[$i]['title'] = $worksheet_title;
		$worksheets[$i]['no_column'] = $no_columns;
		$worksheets[$i]['no_row'] = $highest_row;	
		for ($row = 0; $row < $highest_row; $row++) {
			for ($col = 0; $col < $highest_column_index; $col++) {
				$cell = $worksheet->getCellByColumnAndRow($col,$row+1);
				$val = $cell->getValue();
				$worksheets[$i]['data'][$row][$col] = $val;
			}
		}
		$i++;
	}
	return $worksheets;
}
function parse_excel_column_to_custom_array($filename,$column,$starting_cell_row) {
	$worksheet = array();
	$object_PHPExcel = PHPExcel_IOFactory::load($filename);
	$worksheet = $object_PHPExcel->setActiveSheetIndex(0);
	$worksheet_title = $worksheet->getTitle();
	$highest_row = $worksheet->getHighestRow();
	$col = PHPExcel_Cell::columnIndexFromString($column);
	for ($row = 0; $row < ($highest_row - $starting_cell_row + 1); $row++) {
		$cell = $worksheet->getCellByColumnAndRow($col-1,$row+$starting_cell_row);
		$val = $cell->getValue();
		$worksheet[$row] = $val;
	}
	sort($worksheet);
	return $worksheet;
}
function anti_sql($sql) {
    $sql = str_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|*|--|\)/"),"",$sql);
    return trim((string)strip_tags(addslashes($sql))); #strtolower()
}
/* Triggers */
function comment_like($comid,$uid) {
	global $db;
	$comid = (int)$comid; // Cast to integer for security
	$uid = (int)$uid; // Cast to integer for security
	
	// Check if the user has voted on the comment
	$stmt = $db->link->prepare("SELECT * FROM " . $db->db_prefix . "COMMENT_VOTE WHERE Comment_ID=:comid AND User_ID=:uid");
	$stmt->bindParam(':comid', $comid, PDO::PARAM_INT);
	$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
	$stmt->execute();
	
	$votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	if (count($votes) == 0) {
		// User hasn't voted, insert a new vote record
		$sql_in = "INSERT INTO " . $db->db_prefix . "COMMENT_VOTE (User_ID, Comment_ID, CommentVote_Like, CommentVote_Dislike) VALUES (:uid, :comid, 1, 0)";
		$stmt = $db->link->prepare($sql_in);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->bindParam(':comid', $comid, PDO::PARAM_INT);
		$stmt->execute();
	} else {
		// User has voted, update the vote record
		$vote = $votes[0];
	
		if ($vote['CommentVote_Like'] == 1 && $vote['CommentVote_Dislike'] == 0) {
			// User liked the comment, remove the like
			$sql_update = "UPDATE " . $db->db_prefix . "COMMENT_VOTE SET CommentVote_Like = 0 WHERE Comment_ID=:comid AND User_ID=:uid";
		} elseif ($vote['CommentVote_Like'] == 0 && $vote['CommentVote_Dislike'] == 0) {
			// User didn't vote or voted for dislike, set like and remove dislike
			$sql_update = "UPDATE " . $db->db_prefix . "COMMENT_VOTE SET CommentVote_Like = 1, CommentVote_Dislike = 0 WHERE Comment_ID=:comid AND User_ID=:uid";
		} elseif ($vote['CommentVote_Like'] == 0 && $vote['CommentVote_Dislike'] == 1) {
			// User disliked the comment, remove the dislike
			$sql_update = "UPDATE " . $db->db_prefix . "COMMENT_VOTE SET CommentVote_Like = 1, CommentVote_Dislike = 0 WHERE Comment_ID=:comid AND User_ID=:uid";
		}
	
		$stmt = $db->link->prepare($sql_update);
		$stmt->bindParam(':comid', $comid, PDO::PARAM_INT);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->execute();
	}	
}
function comment_dislike($comid,$uid) {
	global $db;
	$comid = (int)$comid; // Cast to integer for security
	$uid = (int)$uid; // Cast to integer for security
	
	// Check if the user has voted on the comment
	$stmt = $db->link->prepare("SELECT * FROM " . $db->db_prefix . "COMMENT_VOTE WHERE Comment_ID=:comid AND User_ID=:uid");
	$stmt->bindParam(':comid', $comid, PDO::PARAM_INT);
	$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
	$stmt->execute();
	
	$votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	if (count($votes) == 0) {
		// User hasn't voted, insert a new vote record
		$sql_in = "INSERT INTO " . $db->db_prefix . "COMMENT_VOTE (User_ID, Comment_ID, CommentVote_Dislike, CommentVote_Like) VALUES (:uid, :comid, 1, 0)";
		$stmt = $db->link->prepare($sql_in);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->bindParam(':comid', $comid, PDO::PARAM_INT);
		$stmt->execute();
	} else {
		// User has voted, update the vote record
		$vote = $votes[0];
	
		if ($vote['CommentVote_Dislike'] == 1 && $vote['CommentVote_Like'] == 0) {
			// User disliked the comment, remove the dislike
			$sql_update = "UPDATE " . $db->db_prefix . "COMMENT_VOTE SET CommentVote_Dislike = 0 WHERE Comment_ID=:comid AND User_ID=:uid";
		} else {
			// User either liked or didn't vote, set dislike and remove like
			$sql_update = "UPDATE " . $db->db_prefix . "COMMENT_VOTE SET CommentVote_Dislike = 1, CommentVote_Like = 0 WHERE Comment_ID=:comid AND User_ID=:uid";
		}
	
		$stmt = $db->link->prepare($sql_update);
		$stmt->bindParam(':comid', $comid, PDO::PARAM_INT);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->execute();
	}	
}
function latest_post_follow($pid,$uid) {
	global $db;
	$follows = $db->array_load_with_two_identifier('POST_FOLLOW','Post_ID',$pid,'User_ID',$uid);
	sort($follows);
	$follow = $follows[0];
	$count = count($follows);
	if ($count == 0) {
		follow_post($uid,$pid);
	}
}
function post_like($pid,$uid) {
	global $db;
	$pid = (int)$pid; // Cast to integer for security
	$uid = (int)$uid; // Cast to integer for security
	
	// Check if the user has voted on the post
	$stmt = $db->link->prepare("SELECT * FROM " . $db->db_prefix . "POST_VOTE WHERE Post_ID=:pid AND User_ID=:uid");
	$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
	$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
	$stmt->execute();
	
	$votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	if (count($votes) == 0) {
		// User hasn't voted, insert a new vote record
		$sql_in = "INSERT INTO " . $db->db_prefix . "POST_VOTE (User_ID, Post_ID, PostVote_Like, PostVote_Dislike) VALUES (:uid, :pid, 1, 0)";
		$stmt = $db->link->prepare($sql_in);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->execute();
	} else {
		// User has voted, update the vote record
		$vote = $votes[0];
	
		if ($vote['PostVote_Like'] == 1 && $vote['PostVote_Dislike'] == 0) {
			// User liked the post, remove the like
			$sql_update = "UPDATE " . $db->db_prefix . "POST_VOTE SET PostVote_Like = 0 WHERE Post_ID=:pid AND User_ID=:uid";
		} elseif ($vote['PostVote_Like'] == 0 && $vote['PostVote_Dislike'] == 0) {
			// User either didn't vote or voted for dislike, set like and remove dislike
			$sql_update = "UPDATE " . $db->db_prefix . "POST_VOTE SET PostVote_Like = 1, PostVote_Dislike = 0 WHERE Post_ID=:pid AND User_ID=:uid";
		} elseif ($vote['PostVote_Like'] == 0 && $vote['PostVote_Dislike'] == 1) {
			// User disliked the post, remove the dislike
			$sql_update = "UPDATE " . $db->db_prefix . "POST_VOTE SET PostVote_Like = 1, PostVote_Dislike = 0 WHERE Post_ID=:pid AND User_ID=:uid";
		}
	
		$stmt = $db->link->prepare($sql_update);
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->execute();
	}
}
function post_dislike($pid,$uid) {
	global $db;
	$pid = (int)$pid; // Cast to integer for security
	$uid = (int)$uid; // Cast to integer for security
	
	// Check if the user has voted on the post
	$stmt = $db->link->prepare("SELECT * FROM " . $db->db_prefix . "POST_VOTE WHERE Post_ID=:pid AND User_ID=:uid");
	$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
	$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
	$stmt->execute();
	
	$votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	if (count($votes) == 0) {
		// User hasn't voted, insert a new vote record
		$sql_in = "INSERT INTO " . $db->db_prefix . "POST_VOTE (User_ID, Post_ID, PostVote_Dislike, PostVote_Like) VALUES (:uid, :pid, 1, 0)";
		$stmt = $db->link->prepare($sql_in);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->execute();
	} else {
		// User has voted, update the vote record
		$vote = $votes[0];
	
		if ($vote['PostVote_Dislike'] == 1 && $vote['PostVote_Like'] == 0) {
			// User disliked the post, remove the dislike
			$sql_update = "UPDATE " . $db->db_prefix . "POST_VOTE SET PostVote_Dislike = 0 WHERE Post_ID=:pid AND User_ID=:uid";
		} elseif ($vote['PostVote_Dislike'] == 0 && $vote['PostVote_Like'] == 0) {
			// User either didn't vote or voted for like, set dislike and remove like
			$sql_update = "UPDATE " . $db->db_prefix . "POST_VOTE SET PostVote_Dislike = 1, PostVote_Like = 0 WHERE Post_ID=:pid AND User_ID=:uid";
		} elseif ($vote['PostVote_Dislike'] == 0 && $vote['PostVote_Like'] == 1) {
			// User liked the post, remove the like
			$sql_update = "UPDATE " . $db->db_prefix . "POST_VOTE SET PostVote_Dislike = 1, PostVote_Like = 0 WHERE Post_ID=:pid AND User_ID=:uid";
		}
	
		$stmt = $db->link->prepare($sql_update);
		$stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->execute();
	}
}
function post_follow($pid,$uid) {
	global $db;
	$follows = $db->array_load_with_two_identifier('POST_FOLLOW','Post_ID',$pid,'User_ID',$uid);
	sort($follows);
	$follow = $follows[0];
	$count = count($follows);
	if ($count == 0) {
		unfollow_post($uid,$pid);
		follow_post($uid,$pid);
	} elseif ($count == 1) {
		unfollow_post($uid,$pid);
	}
}
function user_follow($uid,$followee_id) {
	global $db;
	$user_follows = $db->array_load_with_two_identifier('USER_FOLLOW','User_ID',$uid,'Followee_ID',$followee_id);
	sort($user_follows);
	$count = count($user_follows);
	if ($count == 0) {
		unfollow_user($uid,$followee_id);
		follow_user($uid,$followee_id);
	} elseif ($count == 1) {
		unfollow_user($uid,$followee_id);
	}
}
function transliterateURL($url) {
    // Transliterate non-ASCII characters to their ASCII equivalents
    $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT', $url);
    
    // Replace spaces and special characters with dashes
    $transliterated = preg_replace('/[^A-Za-z0-9\-]/', '-', $transliterated);
    
    // Remove consecutive dashes and leading/trailing dashes
    $transliterated = preg_replace('/-+/', '-', $transliterated);
    $transliterated = trim($transliterated, '-');
    
    // Convert to lowercase
    $transliterated = strtolower($transliterated);
    
    return $transliterated;
}