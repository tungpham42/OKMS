<?php //Paging for search results page
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
if (isset($_POST['keyword'])) {
	$query = str_replace("'","/",$_POST['keyword']);
	$count = (isset($_POST['count'])) ? $_POST['count']: '';
	$cid = (isset($_POST['cid'])) ? $_POST['cid']: '';
	$page = (isset($_POST['page'])) ? $_POST['page']: '';
	print search_question($query,$cid,$count,$page);
}
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