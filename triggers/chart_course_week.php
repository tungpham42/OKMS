<?php //View chart of questions by course
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
print chart_questions_per_week($cid);
?>