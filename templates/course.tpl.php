<?php //Course page template
$course = course_load($cid);
$rid = (isset($_SESSION['rid'])) ? $_SESSION['rid']: 0;
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
echo ((!course_belonged($cid,$_SESSION['uid']) && $cid != 0 && $rid != 1) || (isset($course['Course_Allowed']) && $course['Course_Allowed'] != 1 && $rid != 3) || ((!is_allowed($_SESSION['uid']) && $rid != 1 && $rid != 3))) ? '': ask_question($rid,$cid,0);
echo '<div id="feeds">';
echo view_course($cid,$uid,10);
echo '</div>';
?>
<script>
setInterval(function(){
	$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"course"});
},1000*60*5);
</script>