<?php //Left menu template
if (isset($_SESSION['uid'])):
	$user = user_load($_SESSION['uid']);
	$email = $user['User_Mail'];
	$default = DEFAULT_AVATAR;
	$grav_url = "http://0.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=".$default."&s=40";
	print '<a class="author" href="?p=user/'.$user['User_Username'].'"><img src="'.$grav_url.'" width="40px"/><div class="name">'.((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username']).'</div></a>'
?>
<?php
endif;
?>
<div id="weeks">
	<?php
	print (isset($cid)) ? style_active_week_menu($cid): style_active_week_menu();
	print (isset($cid)) ? weeks_bar($cid): weeks_bar();
	?>
</div>
<?php
print style_active_course_menu();
print view_courses_by_uid($_SESSION['uid']);
print view_other_courses_by_uid($_SESSION['uid']);
?>
<div class="clear"></div>
<script src="js/animation.js" type="text/javascript"></script>