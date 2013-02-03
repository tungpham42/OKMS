<?php //Paging for front page
require_once '../includes/functions.inc';
require_once '../includes/admin.inc';
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
$option = (isset($_POST['option'])) ? $_POST['option']: 'All courses';
$page = (isset($_POST['page'])) ? $_POST['page']: 1;
$count = (isset($_POST['count'])) ? $_POST['count']: 10;
print front_page_listing($count,$uid,'sort_post_date_descend',$option,$page);
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