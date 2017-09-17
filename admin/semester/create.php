<?php
$semester_code = (isset($_POST['semester_code'])) ? $_POST['semester_code']: '';
$semester_start_date = (isset($_POST['semester_start_date'])) ? strtotime($_POST['semester_start_date']): 0;
$semester_end_date = (isset($_POST['semester_end_date'])) ? strtotime($_POST['semester_end_date']): 0;
if (isset($_POST['submit'])):
	create_semester($semester_code,$semester_start_date,$semester_end_date);
	sleep(1);
	header('location: '.currentURL().'?p=semester');
endif;
?>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />
<form id="form" method="post" action="">
	<table>
		<tr>
			<td><label for="semester_code">Semester code:</label></td>
			<td><input type="text" name="semester_code" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="semester_start_date">Semester start date:</label></td>
			<td><input id="semester_start_date" type="text" name="semester_start_date" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="semester_end_date">Semester end date:</label></td>
			<td><input id="semester_end_date" type="text" name="semester_end_date" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Submit" /></td>
			<td><input type="reset" value="Reset" /></td>
		</tr>
	</table>
</form>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
<script type="text/javascript" charset="utf-8">
	$( "#semester_start_date" ).datepicker({ dateFormat: "yy-mm-dd" , changeYear: true , yearRange: "1920:2020" , defaultDate: '<?php print date('Y-m-d'); ?>'});
	$( "#semester_end_date" ).datepicker({ dateFormat: "yy-mm-dd" , changeYear: true , yearRange: "1920:2020" , defaultDate: '<?php print date('Y-m-d'); ?>'});
</script>
