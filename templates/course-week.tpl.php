<?php //Course week page template
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
if (isset($_SESSION['rid'])):
	print ((!course_belonged($cid,$_SESSION['uid']) && $cid != 0 && $rid != 1) || (isset($course['Course_Allowed']) && $course['Course_Allowed'] != 1 && $rid != 3) || ((!is_allowed($_SESSION['uid']) && $rid != 1 && $rid != 3))) ? '': ask_question($_SESSION['rid'],$course_week['cid'],$course_week['week']);
	print '<div id="feeds">';
	print view_course_week($course_week['cid'],$course_week['week'],10,$uid,'sort_post_date_descend');
	print '</div>';
else:
	print ((!course_belonged($cid,$_SESSION['uid']) && $cid != 0 && $rid != 1) || (isset($course['Course_Allowed']) && $course['Course_Allowed'] != 1 && $rid != 3) || ((!is_allowed($_SESSION['uid']) && $rid != 1 && $rid != 3))) ? '': ask_question(0,$course_week['cid'],$course_week['week']);
	print '<div id="feeds">';
	print view_course_week($course_week['cid'],$course_week['week'],10,$uid,'sort_post_date_descend');
	print '</div>';
endif;
?>
<script>
setInterval(function(){
	$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"course_week"});
},1000*60*5);
</script>