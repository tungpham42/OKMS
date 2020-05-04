<div id="search_bar">
	<form action="/search" method="POST">
		<input type="hidden" name="cid" value="<?php echo $cid; ?>" />
		<input id="search_box" type="text" name="keyword" title="Fill in your search query" size="20" placeholder="Search<?php echo (isset($cid) && $cid != 0) ? ' in '.$course['Course_Code']: ''; ?>.." /><button type="submit" id="search_submit"></button>
	</form>
	<div style="display: none;" id="search_suggestion"></div>
</div>
<script type="text/javascript">
function searchSuggestion() {
	if ($("input#search_box").val() != '') {
		$("#search_suggestion").delay(200).load("/triggers/search.php",{keyword:$("input#search_box").val(),cid:<?php echo $cid; ?>}).slideDown();
	} else if ($("input#search_box").val() == '') {
		$("#search_suggestion").css("display","none");
	}
}
$("input#search_box").keyup(searchSuggestion).change(searchSuggestion);
if ($("input#search_box").val() == '') {
	$("#search_suggestion").css("display","none");
}
</script>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- okms_200x200 -->
<center>
	<ins class="adsbygoogle"
     style="display:inline-block;width:200px;height:200px"
     data-ad-client="ca-pub-3585118770961536"
     data-ad-slot="8174603499"></ins>
 </center>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<?php
echo (isset($_SESSION['uid'])) ? following_list($_SESSION['uid']): '';
echo latest_questions(3);
echo most_commented(3);
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
