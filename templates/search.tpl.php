<?php //Search results page template
if (isset($_GET['q'])) {					
	$query = str_replace("'","/",$_GET['q']);
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