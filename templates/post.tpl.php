<?php //Individual post template
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
if (in_array($pid,$pids)):
	echo view_post($pid,$uid,1);
else:
	echo view_post($pid,$uid,1);
endif;
?>