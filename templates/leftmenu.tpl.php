<?php //Left menu template
if (isset($_SESSION['uid'])):
	$user = user_load($_SESSION['uid']);
	$email = $user['User_Mail'];
	$default = DEFAULT_AVATAR;
	$grav_url = "https://0.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=identicon&s=40";
	echo '<a class="author" href="/user/'.$user['User_Username'].'"><img src="'.$grav_url.'" width="40px"/><div class="name">'.((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username']).'</div></a>'
?>
<?php
endif;
?>
<div id="weeks">
	<?php
	echo (isset($cid)) ? style_active_week_menu($cid): style_active_week_menu();
	echo (isset($cid)) ? weeks_bar($cid): weeks_bar();
	?>
</div>
<?php
echo style_active_course_menu();
echo isset($_SESSION['uid']) ? view_courses_by_uid($_SESSION['uid']) : view_courses_by_uid(0);
echo isset($_SESSION['uid']) ? view_other_courses_by_uid($_SESSION['uid']) : view_other_courses_by_uid(0);
?>
<div class="clear"></div>
<script src="/js/animation.js" type="text/javascript"></script>