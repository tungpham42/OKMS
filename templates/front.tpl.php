<?php //Front page template
$rid = (isset($_SESSION['rid'])) ? $_SESSION['rid']: 0;
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
echo ask_question($rid,0,0);
echo ($uid != 0) ? '<label for="option">Filter: </label>'.select_front_page_filter('option'): '';
echo '<div id="feeds">';
echo front_page_listing(10,$uid,'sort_post_date_descend','All courses');
echo '</div>';
?>