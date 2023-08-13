<?php //Update count comment button
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['pid'])) {
	$pid = $_POST['pid'];
	echo '<script type="text/javascript">
			$("#comments_count_pid_'.$pid.'").html("'.count_comments($pid).' Comment'.((count_comments($pid) == 0 || count_comments($pid) == 1) ? '': 's').'");
			</script>';
}
?>