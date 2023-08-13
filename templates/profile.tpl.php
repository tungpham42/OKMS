<?php //Profile page template
$user = user_load_from_name($username);
$profile_uid = $user['User_ID'];
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
echo ($profile_uid != $uid && $uid != 0) ? following_box($profile_uid): '';
echo '<a title="List of posts that this user has followed so far" class="button" href="/user/'.$username.'/follows">Following posts</a>';
echo '<div id="feeds">';
echo view_profile(10,$profile_uid,$uid,'sort_post_date_descend');
echo '</div>';
?>
<script>
setInterval(function(){
	$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"profile"});
},1000*60*5);
</script>