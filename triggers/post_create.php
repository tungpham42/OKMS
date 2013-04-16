<?php //Create post
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
$uid = $_SESSION['uid'];
$cid = (isset($_POST['cid'])) ? $_POST['cid']: '';
$week = (isset($_POST['week'])) ? $_POST['week']: '';
$title = (isset($_POST['title'])) ? $_POST['title']: '';
$url = (isset($_POST['url'])) ? $_POST['url']: '';
$body = (isset($_POST['body'])) ? $_POST['body']: '';
$answer = (isset($_POST['answer'])) ? $_POST['answer']: '';
$hide = (isset($_POST['hide'])) ? $_POST['hide']: 0;
$rows = mysql_query("SELECT * FROM ".$db->db_prefix."POST WHERE Post_Url='".$url."'");
if(mysql_num_rows($rows) == 0)
{
	create_post($uid,$cid,$week,$title,$url,$body,$answer,$hide);
}
else
{
	echo('URL_EXISTS');
}
?>