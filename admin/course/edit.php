<?php
$cid = isset($_POST['cid']) ? $_POST['cid']: '';
$code = isset($_POST['code']) ? $_POST['code']: '';
$name = isset($_POST['name']) ? $_POST['name']: '';
$new_code = isset($_POST['course_code']) ? $_POST['course_code']: '';
$new_name = isset($_POST['course_name']) ? $_POST['course_name']: '';
if (isset($_POST['submit'])):
	edit_course($_POST['cid'],$new_code,$new_name);
	sleep(1);
	header('location: '.currentURL().'/course');
	print 'Course edited. Click <a href="/course">here</a> to view created courses';
endif;
?>
<form id="form" method="post" action="">
	<input type="hidden" name="cid" value="<?php print $cid; ?>"/>
	<table>
		<tr>
			<td><label for="course_code">New course's code:</label></td>
			<td><input type="text" name="course_code" value="<?php print $code; ?>" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="course_name">New course's name:</label></td>
			<td><input type="text" name="course_name" value="<?php print $name; ?>" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Edit" /></td>
			<td><button onclick="history.go(-1);return false;">Cancel</button></td>
		</tr>
	</table>
</form>