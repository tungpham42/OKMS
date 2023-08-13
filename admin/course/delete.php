<?php
$cid = isset($_POST['cid']) ? $_POST['cid']: '';
$course = course_load($cid);
if (isset($_POST['submit'])):
if (course_is_empty($_POST['id'])):
	delete_course($_POST['id']);
	sleep(1);
	header('location: '.currentURL().'/course');
	echo 'Course deleted. Click <a href="/course">here</a> to view created courses';
else:
	echo '<span style="color: red;">This course contains '.count_posts_from_cid($_POST['id']).' post'.((count_posts_from_cid($_POST['id']) == 0 || count_posts_from_cid($_POST['id']) == 1) ? '': 's').'. Please delete all the posts before deleting the course</span>';
endif;
endif;
?>
<h3>Do you want to delete the course "<?php echo $course['Course_Name']; ?>"?</h3>
<form method="post" action="">
	<input type="hidden" name="id" value="<?php echo $cid; ?>"/>
	<input type="submit" name="submit" value="Delete" />
	<a class="button" href="/course">Cancel</a>
</form>