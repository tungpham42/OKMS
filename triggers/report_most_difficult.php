<?php //Report most difficult posts
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
$count = (isset($_POST['count'])) ? $_POST['count']: 0;
$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
print report_most_difficult($count,$cid);
?>