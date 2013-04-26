<?php //Create comment
require_once '../includes/functions.inc.php';
$pid = (isset($_POST['pid'])) ? $_POST['pid']: '';
$uid = (isset($_POST['uid'])) ? $_POST['uid']: '';
$body = (isset($_POST['body'])) ? $_POST['body']: '';
$hide = (isset($_POST['hide'])) ? $_POST['hide']: '0';
$user = user_load($uid);
create_comment($pid,$uid,$body,$hide);
notify($pid,(($hide == 1) ? 'Anonymous': $user['User_Fullname']),$body);
//unfollow_post($uid,$pid);
//follow_post($uid,$pid);
?>