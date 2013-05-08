<?php //Profile page template
$user = user_load_from_name($username);
$profile_uid = $user['User_ID'];
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
print ($profile_uid != $uid && $uid != 0) ? following_box($profile_uid): '';
print '<a title="List of posts that this user has followed so far" class="button" href="?p=user/'.$username.'/follows">Following posts</a>';
print '<div id="feeds">';
print view_profile(10,$profile_uid,$uid,'sort_post_date_descend');
print '</div>';
?>
<script>
setInterval(function(){
	$("#feeds").load("triggers/feeds_update.php",{feeds_type:"profile"});
},1000*60*5);
</script>