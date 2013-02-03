<?php //Search suggestions for search box
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
if (isset($_POST['keyword'])) {
	$keyword = $_POST['keyword'];
	$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
	$posts = array();
	if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2) {
		$result = mysql_query("SELECT * FROM ".PREFIX."POST WHERE (Post_Title LIKE '%".$keyword."%' OR Post_Question LIKE '%".$keyword."%' OR Post_Answer LIKE '%".$keyword."%' OR Post_URL LIKE '%".$keyword."%')");
	} elseif (!isset($_SESSION['rid']) || $_SESSION['rid'] == 2) {
		$result = mysql_query("SELECT * FROM ".PREFIX."POST WHERE Post_Current=1 AND (Post_Title LIKE '%".$keyword."%' OR Post_Question LIKE '%".$keyword."%' OR Post_Answer LIKE '%".$keyword."%' OR Post_URL LIKE '%".$keyword."%')");
	}
	if ($result) {
		while($row = mysql_fetch_assoc($result)) {
			$posts[] = $row;
		}
	}
	if ($cid != 0) {
		$posts = array_filter($posts, array(new Filter($cid), 'filter_cid'));
	}
	sort($posts);
	usort($posts,'sort_post_date_descend');
	if (count($posts) == 0) {
		print '<span style="padding: 15px;">There is no result</span>';
	}
	else {
		$count = (count($posts) < 5) ? count($posts): 5;
		print '<span style="padding: 15px;">'.count($posts).' result'.((count($posts) == 1) ? '': 's').'</span>';
		print '<ul id="suggestions">';
		for ($i = 0; $i < $count; $i++) {
			if (isset($posts[$i]['Post_ID'])) {
				$user = user_load($posts[$i]['User_ID']);
				print '<li class="suggestion'.(($i == 0) ? ' first': '').'"><a class="post_title'.(($i == 0) ? ' first': '').'" href="?p=question/'.$posts[$i]['Post_URL'].'">'.$posts[$i]['Post_Title'].'</a><br/><span class=post_author>'.((isset($posts[$i]['Post_Hide_Name']) && $posts[$i]['Post_Hide_Name'] == 1 || !user_existed($posts[$i]['User_ID'])) ? 'Anonymous': ((isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username'])).'</span></li>';
			}
		}
		print (count($posts) > 5) ? '<li class="suggestion"><a id="all_results">Show all results</a></li>': '';
		print '</ul>';
	}
}
?>
<script type="text/javascript">
$("#all_results").click(function(){
	$("#search_bar > form").submit();
});
</script>