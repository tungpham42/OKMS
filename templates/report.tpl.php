<?php //Report page template
$report_type = (isset($_POST['report_type'])) ? $_POST['report_type']: 0;
print select_report_type('report_type',$report_type);
?>
<div id="report_section"></div>
<script type="text/javascript">
$("#report_section").load("/triggers/report.php",{report_type:'Number of questions per week'});
$("select#report_type").change(function(){
	$("#report_section").load("/triggers/report.php",{report_type:$(this).val()});
});
</script>