<?php //Profile page template
$user = user_load_from_name($username);
$profile_uid = $user['User_ID'];
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
print '<a title="List of posts that this user has followed so far" class="button" href="?p=user/'.$username.'/follows">Following posts</a>';
print '<div id="feeds">';
print view_profile(10,$profile_uid,$uid,'sort_post_date_descend');
print '</div>';
?>