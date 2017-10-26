<?php
$pid = isset($_POST['pid']) ? $_POST['pid']: '';
$post = post_load($pid);
$uid = $_SESSION['uid'];
$old_cid = isset($_POST['old_cid']) ? $_POST['old_cid']: $post['Course_ID'];
$old_week = isset($_POST['old_week']) ? $_POST['old_week']: $post['Post_Week'];
$old_title = isset($_POST['old_title']) ? $_POST['old_title']: $post['Post_Title'];
$old_url = isset($_POST['old_url']) ? $_POST['old_url']: $post['Post_URL'];
$old_body = isset($_POST['old_body']) ? $_POST['old_body']: $post['Post_Question'];
$old_answer = isset($_POST['old_answer']) ? $_POST['old_answer']: $post['Post_Answer'];
$cid = isset($_POST['cid']) ? $_POST['cid']: '';
$week = isset($_POST['week']) ? $_POST['week']: '';
$title = isset($_POST['title']) ? $_POST['title']: '';
$url = isset($_POST['url']) ? $_POST['url']: '';
$body = isset($_POST['body']) ? $_POST['body']: '';
$answer = isset($_POST['answer']) ? $_POST['answer']: '';
if (isset($_POST['submit'])):
	if ($uid == $post['User_ID'] || $_SESSION['rid'] == 1 || $_SESSION['rid'] == 3):
		edit_post($_POST['pid'],$cid,$week,$title,$url,$body,$answer);
		sleep(1);
		header('location: '.currentURL().'?p=question/'.$url);
		print 'Post edited. Click <a href="/?p=post">here</a> to view posts';
	else:
		print 'Not authorized';
	endif;
endif;
?>
<link rel="stylesheet" type="text/css" href="markitup/skins/markitup/style.css" />
<link rel="stylesheet" type="text/css" href="markitup/sets/html/style.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="markitup/jquery.markitup.js"></script>
<script type="text/javascript" src="markitup/sets/html/set.js"></script>
<script language="javascript">
$(document).ready(function()	{
   //$('#body').markItUp(myHtmlSettings);
   //$('#answer').markItUp(myHtmlSettings);
});
</script>
<form id="form" method="post" action="">
	<input type="hidden" name="pid" value="<?php print $pid; ?>"/>
	<table>
		<tr id="course">
			<td><label for="type">Course:</label></td>
			<td><?php print select_course('cid',$old_cid); ?></td>
		</tr>
		<tr id="week">
			<td><label for="type">Week:</label></td>
			<td><?php print select_week('week',$old_week); ?></td>
		</tr>
		<tr style="display:none">
			<td><label for="title">Title:</label></td>
			<td><input type="text" name="title" value="<?php print $old_title; ?>" size="60" maxlength="128" class="required" /><input type="hidden" name="url" value="<?php print $old_url; ?>" /></td>
		</tr>
		<tr>
			<td><label for="body">Body:</label></td>
			<td><textarea id="body" cols="60" rows="20" name="body" class="required"><?php print $old_body; ?></textarea></td>
		</tr>
		<?php
		if ($_SESSION['rid'] != 2):
		?>
		<tr id="answer-row">
			<td><label for="answer">Answer:</label></td>
			<td><textarea id="answer" cols="60" rows="20" name="answer" ><?php print $old_answer; ?></textarea></td>
		</tr>
		<?php
		endif;
		?>
		<tr>
			<td><input type="submit" name="submit" value="Edit" /></td>
			<td><a class="button" href="/?p=question/<?php print $post['Post_URL']; ?>">Cancel</a></td>
		</tr>
	</table>
</form>