<?php //Course page template
$course = course_load($cid);
$rid = (isset($_SESSION['rid'])) ? $_SESSION['rid']: 0;
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
print ((!course_belonged($cid,$_SESSION['uid']) && $cid != 0 && $rid != 1) || (isset($course['Course_Allowed']) && $course['Course_Allowed'] != 1 && $rid != 3) || ((!is_allowed($_SESSION['uid']) && $rid != 1 && $rid != 3))) ? '': ask_question($rid,$cid,0);
print '<div id="feeds">';
print view_course($cid,$uid,10);
print '</div>';
?>