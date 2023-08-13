<?php //Search results page template
if (isset($_POST['keyword'])) {					
	$query = str_replace("'","/",$_POST['keyword']);
	$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
	echo '<div id="feeds">';
	if ($query == '') {
		echo '<b>Please type the keyword to search</b>';
	} else {
		echo search_question($query,$cid,10);
	}
	echo '</div>';
}
?>