<div id="search_bar">
	<form action="/search" method="GET">
		<input id="search_box" type="text" name="q" title="Fill in your search query" size="20" placeholder="Search<?php echo (isset($cid) && $cid != 0) ? ' in '.$course['Course_Code']: ''; ?>.." />
		<input type="hidden" name="cid" value="<?php echo $cid; ?>" />
		<button type="submit" id="search_submit"></button>
	</form>
	<div id="search_suggestion"></div>
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
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3585118770961536" crossorigin="anonymous"></script>
<!-- OKMS_res -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-3585118770961536"
     data-ad-slot="2138902239"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<?php
echo (isset($_SESSION['uid'])) ? following_list($_SESSION['uid']): '';
echo latest_questions(5);
echo most_commented(5);
?>
<br/>
<div class="heading">External Links</div>
<br/>
<div class="quicklinks">
	<a target="_blank" href="http://en.wikipedia.org"><img class="quicklink" alt="wiki" src="/images/WIKI.jpg" width="100px"/></a>
	<a target="_blank" href="https://mail.google.com/a/rmit.edu.vn"><img class="quicklink" alt="mail" src="/images/MAIL.jpg" width="100px"/></a>
	<a target="_blank" href="http://www.rmit.edu.au"><img class="quicklink" alt="australia" src="/images/AUS.jpg" width="100px"/></a>
	<a target="_blank" href="https://online.rmit.edu.vn/"><img class="quicklink" alt="intranet" src="/images/INTRANET.jpg" width="100px"/></a>
</div>
