<?php
$uid = isset($_POST['uid']) ? $_POST['uid']: '';
$user = user_load($uid);
if (isset($_POST['submit'])):
	delete_user($_POST['id']);
	sleep(1);
	header('location: '.currentURL().'/user');
	echo 'User deleted. Click <a href="/user">here</a> to view created users';
endif;
?>
<h3>Do you want to delete the user "<?php echo $user['User_Username']; ?>"?</h3>
<form method="post" action="">
	<input type="hidden" name="id" value="<?php echo $uid; ?>"/>
	<input type="submit" name="submit" value="Delete" />
	<button onclick="history.go(-1);return false;">Cancel</button>
</form>