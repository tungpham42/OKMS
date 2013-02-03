<?php //Search results page template
if (isset($_POST['keyword'])) {					
	$query = str_replace("'","/",$_POST['keyword']);
	$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
	print '<div id="feeds">';
	if ($query == '') {
		print '<b>Please type the keyword to search</b>';
	} else {
		print search_question($query,$cid,10);
	}
	print '</div>';
}
?>