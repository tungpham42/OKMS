<?php
$rid = isset($_POST['rid']) ? $_POST['rid']: '';
$role = role_load($rid);
if (isset($_POST['submit'])):
	delete_role($_POST['id']);
	sleep(1);
	header('location: '.currentURL().'/role');
	echo 'Role deleted. Click <a href="/role">here</a> to view created roles';
endif;
?>
<h3>Do you want to delete the role "<?php echo $role['Role_Name']; ?>"?</h3>
<form method="post" action="">
	<input type="hidden" name="id" value="<?php echo $rid; ?>"/>
	<input type="submit" name="submit" value="Delete" />
	<button onclick="history.go(-1);return false;">Cancel</button>
</form>