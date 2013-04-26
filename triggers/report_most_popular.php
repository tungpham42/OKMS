<?php //Report most popular posts
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$count = (isset($_POST['count'])) ? $_POST['count']: 0;
$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
print report_most_popular($count,$cid);
?>