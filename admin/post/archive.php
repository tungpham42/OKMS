<?php
$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
echo select_course('cid',$cid);
echo '<div id="admin_archive_section">';
echo list_archives($cid,10,1);
echo '</div>';
?>
<script type="text/javascript">
$("select#cid").change(function(){
	$("#admin_archive_section").load("triggers/admin_archive.php",{cid:$(this).val(),count:10,page:1});
});
</script>