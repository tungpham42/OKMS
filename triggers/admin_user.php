<?php //List users by role
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$rid = (isset($_POST['rid'])) ? $_POST['rid']: 0;
$count = (isset($_POST['count'])) ? $_POST['count']: 10;
$page = (isset($_POST['page'])) ? $_POST['page']: 1;
echo list_users($rid,$count,$page);
?>
<script type="text/javascript">
$("a.button.disabled").click(function(){
	openLogin();
});
$('#admin_user_section [title]').qtip({
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