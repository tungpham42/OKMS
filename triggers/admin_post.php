<?php //List posts by courses
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
$count = (isset($_POST['count'])) ? $_POST['count']: 10;
$page = (isset($_POST['page'])) ? $_POST['page']: 1;
print list_posts($cid,$count,$page);
?>
<script type="text/javascript">
$("a.button.disabled").click(function(){
	openLogin();
});
$('#admin_post_section [title]').qtip({
	style: {
		padding: 7,
		background: '#404041',
		color: 'white',
		fontSize: '10px',
		textAlign: 'center',
		border: {
			width: 2,
			color: 'white'
		},
		tip: 'topLeft',
		name: 'dark' // Inherit the rest of the attributes from the preset dark style
	},
	position: {
		corner: {
		target: 'bottomMiddle',
		tooltip: 'topLeft'
		}
	}
});
</script>