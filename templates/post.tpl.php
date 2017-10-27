<?php //Individual post template
$uid = (isset($_SESSION['uid'])) ? $_SESSION['uid']: 0;
if (in_array($pid,$pids)):
	print view_post($pid,$uid,1);
else:
	print view_post($pid,$uid,1);
endif;
?>
<script>
setInterval(function(){
	$("#feeds").load("/triggers/feeds_update.php",{feeds_type:"post"});
},1000*60*5);
</script>