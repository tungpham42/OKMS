<?php
$role_name = (isset($_POST['role_name'])) ? $_POST['role_name']: '';
if (isset($_POST['submit'])):
	create_role($role_name);
	sleep(1);
	header('location: '.currentURL().'?p=role');
	print 'Role created. Click <a href="?p=role">here</a> to view created roles';
endif;
?>
<form id="form" method="post" action="">
	<table>
		<tr>
			<td><label for="role_name">Role's name:</label></td>
			<td><input type="text" name="role_name" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Submit" /></td>
			<td><input type="reset" value="Reset" /></td>
		</tr>
	</table>
</form>