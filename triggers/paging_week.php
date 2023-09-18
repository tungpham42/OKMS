<?php //Paging for week pages
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
$week = (isset($_POST['week'])) ? $_POST['week']: '';
$count = (isset($_POST['count'])) ? $_POST['count']: '';
$page = (isset($_POST['page'])) ? $_POST['page']: 1;
echo view_week($week,$count,$uid,'sort_post_date_descend',$page);
?>
<script type="text/javascript">
$("a.button.disabled").click(function(){
	openLogin();
});
$('#feeds [title]').qtip({
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