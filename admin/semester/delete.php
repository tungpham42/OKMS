<?php
$semid = isset($_POST['semid']) ? $_POST['semid']: '';
$semester = semester_load($semid);
if (isset($_POST['submit'])):
	delete_role($_POST['id']);
	sleep(1);
	header('location: '.currentURL().'/semester');
endif;
?>
<h3>Do you want to delete the semester "<?php print $semester['Semester_Code']; ?>"?</h3>
<form method="post" action="">
	<input type="hidden" name="id" value="<?php print $semid; ?>"/>
	<input type="submit" name="submit" value="Delete" />
	<button onclick="history.go(-1);return false;">Cancel</button>
</form>