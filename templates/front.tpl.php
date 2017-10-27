<?php //Front page template
$rid = (isset($_SESSION['rid'])) ? $_SESSION['rid']: 0;
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
print ask_question($rid,0,0);
print ($uid != 0) ? '<label for="option">Filter: </label>'.select_front_page_filter('option'): '';
print '<div id="feeds">';
print front_page_listing(10,$uid,'sort_post_date_descend','All courses');
print '</div>';
?>
<script>
setInterval(function(){
	$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"front"});
},1000*60*5);
</script>