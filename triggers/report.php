<?php //Report
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['report_type'])) {
	$report_type = $_POST['report_type'];
	$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
	if ($report_type == 'Number of questions per course') {
		print chart_questions_per_course();
	} elseif ($report_type == 'Number of questions per week') {
		print chart_questions_per_week($cid);
	} elseif ($report_type == 'Most popular questions') {
		print report_most_popular(5);
	} elseif ($report_type == 'Most difficult questions') {
		print report_most_difficult(5);
	}
}
?>