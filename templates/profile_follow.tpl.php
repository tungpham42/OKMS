<?php //Profile follow page template
$user = user_load_from_name($username);
$profile_uid = $user['User_ID'];
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
echo '<a class="button" href="/user/'.$username.'">Back to profile</a>';
echo '<div id="feeds">';
echo view_profile_follow(10,$profile_uid,$uid,'sort_post_date_descend');
echo '</div>';
?>