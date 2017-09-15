<?php //Search suggestions for search box
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['keyword'])) {
	$keyword = $_POST['keyword'];
	$users = array();
	$result = mysqli_query($db->link, "SELECT * FROM ".PREFIX."USER WHERE (User_Username LIKE '%".$keyword."%' OR User_Fullname LIKE '%".$keyword."%' OR User_Mail LIKE '%".$keyword."%')");
	if ($result) {
		while($row = mysqli_fetch_assoc($result)) {
			$users[] = $row;
		}
	}
	sort($users);
	usort($users,'sort_user_ascend');
	if (count($users) == 0) {
		print '<span style="padding: 15px;">There is no result</span>';
	}
	else {
		$count = (count($users) < 5) ? count($users): 5;
		print '<span style="padding: 15px;">'.count($users).' result'.((count($users) == 1) ? '': 's').'</span>';
		print '<ul id="suggestions">';
		for ($i = 0; $i < $count; $i++) {
			if (isset($users[$i]['User_ID'])) {
				print '<li class="suggestion'.(($i == 0) ? ' first': '').'"><a class="post_title'.(($i == 0) ? ' first': '').'" href="?p=user/'.$users[$i]['User_ID'].'">'.$users[$i]['User_Fullname'].'</a><br/><span class=post_author>'.$users[$i]['User_Username'].'</span></li>';
			}
		}
		print (count($users) > 5) ? '<li class="suggestion"><a id="all_results">Show all results</a></li>': '';
		print '</ul>';
	}
}
?>
<script type="text/javascript">
$("#all_results").click(function(){
	$("#search_bar > form").submit();
});
</script>