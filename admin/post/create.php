<?php
$uid = $_SESSION['uid'];
$post_cid = (isset($_POST['post_cid'])) ? $_POST['post_cid']: '';
$post_course = course_load($post_cid);
$cid = (isset($_POST['cid'])) ? $_POST['cid']: '';
$week = (isset($_POST['week'])) ? $_POST['week']: '';
$title = (isset($_POST['title'])) ? $_POST['title']: '';
$url = (isset($_POST['url'])) ? $_POST['url']: '';
$body = (isset($_POST['body'])) ? $_POST['body']: '';
$answer = (isset($_POST['answer'])) ? $_POST['answer']: '';
if (isset($_POST['submit'])):
	create_post($uid,$cid,$week,$title,$url,$body,$answer);
	if(mysql_affected_rows($link)==1)
	{
		sleep(1);
		header('location: '.currentURL().'/post');
		print 'Post created. Click <a href="/post">here</a> to view created posts';
	}
		else print 'Post not created';
endif;
?>
<link rel="stylesheet" type="text/css" href="markitup/skins/markitup/style.css" />
<link rel="stylesheet" type="text/css" href="markitup/sets/html/style.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="markitup/sets/html/set.js"></script>
<script language="javascript">
$(document).ready(function()	{
   $('#body').markItUp(myHtmlSettings);
   $('#answer').markItUp(myHtmlSettings);
});
</script>
<form id="form" method="post" action="">
	<table>
		<tr id="course">
			<td><label for="title">Course:</label></td>
			<td><?php print (isset($_POST['post_cid'])) ? $post_course['Course_Name'].'<input type="hidden" name="cid" value="'.$post_cid.'" />': select_course('cid'); ?></td>
		</tr>
		<tr id="week">
			<td><label for="title">Week:</label></td>
			<td><?php print select_week('week'); ?></td>
		</tr>
		<tr>
			<td><label for="title">Title:</label></td>
			<td><input type="text" name="title" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="url">URL Alias:</label></td>
			<td><input type="text" name="url" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="body">Body:</label></td>
			<td><textarea id="body" cols="60" rows="20" name="body" class="required"></textarea></td>
		</tr>
		<tr id="answer-row">
			<td><label for="answer">Answer:</label></td>
			<td><textarea id="answer" cols="60" rows="20" name="answer" class="required"></textarea></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Create" /></td>
			<td><input type="reset" value="Reset" /></td>
		</tr>
	</table>
</form>