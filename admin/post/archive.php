<?php
$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
print select_course('cid',$cid);
print '<div id="admin_archive_section">';
print list_archives($cid,10,1);
print '</div>';
?>
<script type="text/javascript">
$("select#cid").change(function(){
	$("#admin_archive_section").load("triggers/admin_archive.php",{cid:$(this).val(),count:10,page:1});
});
</script>