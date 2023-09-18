<?php //Week page template
$rid = (isset($_SESSION['rid'])) ? $_SESSION['rid']: 0;
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
echo ask_question($rid,0,$week);
echo '<div id="feeds">';
echo view_week($week,10,$uid,'sort_post_date_descend');
echo '</div>';
?>
<script>
setInterval(function(){
	$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"week", cid: "<?php echo $cid; ?>", week: "<?php echo $week; ?>"});
},1000*30);
</script>