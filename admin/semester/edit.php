<?php
$semid = isset($_POST['semid']) ? $_POST['semid']: '';
$old_semester_code = (isset($_POST['old_semester_code'])) ? $_POST['old_semester_code']: '';
$old_semester_start_date = (isset($_POST['old_semester_start_date'])) ? $_POST['old_semester_start_date']: 0;
$old_semester_end_date = (isset($_POST['old_semester_end_date'])) ? $_POST['old_semester_end_date']: 0;
$semester_code = (isset($_POST['semester_code'])) ? $_POST['semester_code']: '';
$semester_start_date = (isset($_POST['semester_start_date'])) ? strtotime($_POST['semester_start_date']): 0;
$semester_end_date = (isset($_POST['semester_end_date'])) ? strtotime($_POST['semester_end_date']): 0;
if (isset($_POST['submit'])):
	edit_semester($semid,$semester_code,$semester_start_date,$semester_end_date);
	sleep(1);
	header('location: '.currentURL().'?p=semester');
endif;
?>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />
<form id="form" method="post" action="">
	<input type="hidden" name="semid" value="<?php print $semid; ?>"/>
	<table>
		<tr>
			<td><label for="semester_code">Semester code:</label></td>
			<td><input type="text" name="semester_code" size="60" maxlength="128" class="required" value="<?php print $old_semester_code; ?>" /></td>
		</tr>
		<tr>
			<td><label for="semester_start_date">Semester start date:</label></td>
			<td><input id="semester_start_date" type="text" name="semester_start_date" size="60" maxlength="128" class="required" value="<?php print date('Y-m-d',$old_semester_start_date); ?>" /></td>
		</tr>
		<tr>
			<td><label for="semester_end_date">Semester end date:</label></td>
			<td><input id="semester_end_date" type="text" name="semester_end_date" size="60" maxlength="128" class="required" value="<?php print date('Y-m-d',$old_semester_end_date); ?>" /></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Edit" /></td>
			<td><button onclick="history.go(-1);return false;">Cancel</button></td>
		</tr>
	</table>
</form>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
<script type="text/javascript" charset="utf-8">
	$( "#semester_start_date" ).datepicker({ dateFormat: "yy-mm-dd" , changeYear: true , yearRange: "1920:2020" , defaultDate: '<?php print date('Y-m-d',$old_semester_start_date); ?>'});
	$( "#semester_end_date" ).datepicker({ dateFormat: "yy-mm-dd" , changeYear: true , yearRange: "1920:2020" , defaultDate: '<?php print date('Y-m-d',$old_semester_end_date); ?>'});
</script>
