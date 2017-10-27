<?php
$course_code = (isset($_POST['course_code'])) ? $_POST['course_code']: '';
$course_name = (isset($_POST['course_name'])) ? $_POST['course_name']: '';
if (isset($_POST['submit'])):
	create_course($course_code,$course_name);
	sleep(1);
	header('location: '.currentURL().'/?p=course/'.$course_code);
	print 'Course created. Click <a href="/?p=course">here</a> to view created courses';
endif;
?>
<form id="form" method="post" action="">
	<table>
		<tr>
			<td><label for="course_code">Course code:</label></td>
			<td><input type="text" name="course_code" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="course_name">Course name:</label></td>
			<td><input type="text" name="course_name" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Submit" /></td>
			<td><input type="reset" value="Reset" /></td>
		</tr>
	</table>
</form>