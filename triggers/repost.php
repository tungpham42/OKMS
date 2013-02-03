<?php //Repost
require_once '../includes/functions.inc';
$uid = (isset($_POST['uid'])) ? $_POST['uid']: '';
$cid = (isset($_POST['cid'])) ? $_POST['cid']: '';
$week = (isset($_POST['week'])) ? $_POST['week']: '';
$title = (isset($_POST['title'])) ? $_POST['title']: '';
$url = (isset($_POST['url'])) ? $_POST['url']: '';
$body = (isset($_POST['body'])) ? $_POST['body']: '';
$answer = (isset($_POST['answer'])) ? $_POST['answer']: '';
$repostid = (isset($_POST['repostid'])) ? $_POST['repostid']: '';
repost($uid,$cid,$week,$title,$url,$body,$answer,$repostid);
header('location: ../?p=post');
?>