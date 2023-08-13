<?php //Validate ask
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$uid = $_SESSION['uid'];
$rid = (isset($_POST['rid'])) ? $_POST['rid']: '';
$pid = (isset($_POST['pid'])) ? $_POST['pid']: '';
$post = post_load($pid);
$cid = $post['Course_ID'];
$course = course_load($cid);
echo ((!course_belonged($cid,$_SESSION['uid']) && $cid != 0 && $_SESSION['rid'] != 1) ? ' not_belonged': "").((isset($_SESSION['rid']) && $_SESSION['rid'] == 1) ? ' is_admin': "").((isset($_SESSION['rid']) && $course['Course_For_Guest'] == 1) ? ' guest_mode': "");
?>