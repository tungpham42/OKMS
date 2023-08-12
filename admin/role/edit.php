<?php
$rid = isset($_POST['rid']) ? $_POST['rid']: '';
$name = isset($_POST['name']) ? $_POST['name']: '';
$new = isset($_POST['role_name']) ? $_POST['role_name']: '';
if (isset($_POST['submit'])):
	edit_role($_POST['rid'],$new);
	sleep(1);
	header('location: '.currentURL().'/role');
	print 'Role edited. Click <a href="/role">here</a> to view created roles';
endif;
?>
<form id="form" method="post" action="">
	<input type="hidden" name="rid" value="<?php print $rid; ?>"/>
	<table>
		<tr>
			<td><label for="role_name">New role's name:</label></td>
			<td><input type="text" name="role_name" value="<?php print $name; ?>" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Edit" /></td>
			<td><button onclick="history.go(-1);return false;">Cancel</button></td>
		</tr>
	</table>
</form>