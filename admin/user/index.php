<div style="float: right" id="search_bar">
	<form action="" method="POST">
		<input id="search_user_box" type="text" name="keyword" title="Fill in your search query" size="20" placeholder="Search user.." />
	</form>
	<div style="display: none;" id="search_user_suggestion"></div>
</div>
<script type="text/javascript">
function searchUserSuggestion() {
	if ($("input#search_user_box").val() != '') {
		$("#search_user_suggestion").delay(200).load("triggers/search_user.php",{keyword:$("input#search_user_box").val()}).slideDown();
	} else {
		$("#search_user_suggestion").css("display","none");
	}
}
$("input#search_user_box").keyup(searchUserSuggestion).change(searchUserSuggestion);
if ($("input#search_user_box").val() == '') {
	$("#search_user_suggestion").css("display","none");
}
</script>
<?php
$rid = (isset($_POST['rid'])) ? $_POST['rid']: 0;
echo select_role('rid',$rid);
echo '<div id="admin_user_section">';
echo list_users($rid,10,1);
echo '</div>';
?>
<script type="text/javascript">
$("select#rid").change(function(){
	$("#admin_user_section").load("triggers/admin_user.php",{rid:$(this).val(),count:10,page:1});
});
</script>