<?php //Profile follow page template
$user = user_load_from_name($username);
$profile_uid = $user['User_ID'];
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
print '<a class="button" href="/user/'.$username.'">Back to profile</a>';
print '<div id="feeds">';
print view_profile_follow(10,$profile_uid,$uid,'sort_post_date_descend');
print '</div>';
?>
<script>
setInterval(function(){
	$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"profile_follow"});
},1000*60*5);
</script>