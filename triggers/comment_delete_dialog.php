<?php //Delete comment
require_once '../includes/functions.inc';
$comid = (isset($_POST['comid'])) ? $_POST['comid']: '';
$pid = (isset($_POST['pid'])) ? $_POST['pid']: '';
$post = post_load($pid);
?>
<h5>Do you really want to delete this comment?</h5>
<a class="button" id="confirm_delete_comment_comid_<?php print $comid; ?>">Delete</a>
<a class="button" id="cancel_delete_comment_comid_<?php print $comid; ?>">Cancel</a>
<script type="text/javascript">
$("#confirm_delete_comment_comid_<?php print $comid; ?>").click(function(){
	commentDelete(<?php print $comid; ?>,<?php print $pid; ?>);
});
$("#cancel_delete_comment_comid_<?php print $comid; ?>").click(function(){
	closeWrapBox();
});
</script>