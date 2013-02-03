<?php
$pid = isset($_POST['pid']) ? $_POST['pid']: '';
$post = post_load($pid);
$uid = $_SESSION['uid'];
if (isset($_POST['submit'])):
	delete_post($_POST['pid']);
	sleep(1);
	header('location: '.currentURL().'?p=post');
	print 'Post deleted. Click <a class="button" href="?p=post">here</a> to view created posts';
else:
?>
<h3>Do you want to delete the post "<?php print $post['Post_Title']; ?>"?</h3>
<form method="post" action="">
	<input type="hidden" name="pid" value="<?php print $pid; ?>"/>
	<input type="submit" name="submit" value="Delete" />
	<button onclick="history.go(-1);return false;">Cancel</button></td>
</form>
<?php
endif;
?>