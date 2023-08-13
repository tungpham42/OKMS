<?php
$cid = (isset($_POST['cid'])) ? $_POST['cid']: '';
$coor_uid = (isset($_POST['coor_uid'])) ? $_POST['coor_uid']: '';
$course = course_load($cid);
if (isset($_POST['submit'])):
	promote_user($coor_uid,$cid);
	sleep(1);
	header('location: '.currentURL().'/course');
endif;
?>
<form id="form" method="post" action="">
	<input type="hidden" name="cid" value="<?php echo $cid; ?>"/>
	<table style="width: 600px;">
		<tr>
			<td><label for="coor_uid">Select a coordinator from course's lecturers:</label></td>
			<td><?php echo select_coor_uid('coor_uid',$cid); ?></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Promote" /></td>
			<td><a class="button" href="/course">Cancel</a></td>
		</tr>
	</table>
</form>