<?php //Validate ask
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$uid = $_SESSION['uid'];
$rid = (isset($_POST['rid'])) ? $_POST['rid']: '';
$cid = (isset($_POST['cid'])) ? $_POST['cid']: '';
$is_guest = false;
$cids = cids_load_all();
for ($i = 0; $i < count($cids); $i++) {
    $my_course[$i] = course_load($cids[$i]);
    if ($my_course[$i]['Course_For_Guest'] == 1) {
        $is_guest = true;
    }
}
$course = course_load($cid);
echo (($rid == 0) ? 'not_loggedin': '').((!course_belonged($cid,$_SESSION['uid']) && $cid != 0 && $rid != 1) ? ' not_belonged': '').((isset($course['Course_Allowed']) && $course['Course_Allowed'] != 1 && $rid != 3) ? ' not_allowed': '').((isset($_SESSION['rid']) && $_SESSION['rid'] == 1) ? ' is_admin': '').((!is_enroled($_SESSION['uid'])) ? ' not_enroled': '').((!is_allowed($_SESSION['uid']) && $rid != 1 && $rid != 3) ? ' no_course': '').(($is_guest) ? ' guest_mode': '');
?>