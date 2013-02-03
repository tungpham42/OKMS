<?php
$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
print select_course('cid',$cid);
print '<div id="admin_post_section">';
print list_posts($cid,10,1);
print '</div>';
?>
<script type="text/javascript">
$("select#cid").change(function(){
	$("#admin_post_section").load("triggers/admin_post.php",{cid:$(this).val(),count:10,page:1});
});
</script>