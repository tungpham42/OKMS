<div id="search_bar">
	<form action="?p=search" method="POST">
		<input type="hidden" name="cid" value="<?php print $cid; ?>" />
		<input id="search_box" type="text" name="keyword" title="Fill in your search query" size="20" placeholder="Search.." /><button type="submit" id="search_submit"></button>
	</form>
	<div style="display: none;" id="search_suggestion"></div>
</div>
<script type="text/javascript">
function searchSuggestion() {
	if ($("input#search_box").val() != '') {
		$("#search_suggestion").delay(200).load("triggers/search.php",{keyword:$("input#search_box").val(),cid:<?php print $cid; ?>}).slideDown();
	} else if ($("input#search_box").val() == '') {
		$("#search_suggestion").css("display","none");
	}
}
$("input#search_box").keyup(searchSuggestion).change(searchSuggestion);
if ($("input#search_box").val() == '') {
	$("#search_suggestion").css("display","none");
}
</script>
<?php
print latest_questions(3);
print most_commented(3);
?>
<br/>
<div class="heading">External Links</div>
<br/>
<div class="quicklinks">
	<a target="_blank" href="http://en.wikipedia.org"><img class="quicklink" src="images/WIKI.jpg" width="100px"/></a>
	<a target="_blank" href="https://mail.google.com/a/rmit.edu.vn"><img class="quicklink" src="images/MAIL.jpg" width="100px"/></a>
	<a target="_blank" href="http://www.rmit.edu.au"><img class="quicklink" src="images/AUS.jpg" width="100px"/></a>
	<a target="_blank" href="https://online.rmit.edu.vn/"><img class="quicklink" src="images/INTRANET.jpg" width="100px"/></a>
</div>