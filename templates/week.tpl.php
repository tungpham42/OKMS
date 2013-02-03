<?php //Week page template
$rid = (isset($_SESSION['rid'])) ? $_SESSION['rid']: 0;
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
print ask_question($rid,0,$week);
print '<div id="feeds">';
print view_week($week,10,$uid,'sort_post_date_descend');
print '</div>';
?>