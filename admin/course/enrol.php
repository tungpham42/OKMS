<?php
$cid = (isset($_POST['cid'])) ? $_POST['cid']: '';
$course = course_load($cid);
$users = users_load_by_cid($cid);
$users_diff = array();
$uids = uids_load_all_by_rid(2);
$course_uids = uids_load_from_cid($cid);
$diff_uids = array_diff($uids,$course_uids);
foreach ($diff_uids as $diff_uid) {
	$user_diff = user_load($diff_uid);
	$users_diff[] = $user_diff;
}
$users = array_filter($users, array(new filter('2'), 'filter_rid'));
$users_diff = array_filter($users_diff, array(new filter('2'), 'filter_rid'));
$users_label = 'students';
sort($users);
sort($users_diff);
$to_uids = (isset($_POST['toBox'])) ? $_POST['toBox']: array();
$from_uids = (isset($to_uids)) ? array_diff($uids,$to_uids): $uids;
sort($from_uids);
sort($to_uids);
if (isset($_POST['submit'])):
	foreach ($from_uids as $from_uid) {
		delete_course_user($cid,$from_uid);
	}
	if (isset($to_uids)) {
		foreach ($to_uids as $to_uid) {
			delete_course_user($cid,$to_uid);
			create_course_user($cid,$to_uid);
		}
	} else {
		foreach ($uids as $uid) {
			delete_course_user($cid,$uid);
		}	
	}
	sleep(1);
	header('location: '.currentURL().'?p=course');
endif;
?>
<script type="text/javascript" src="js/multipleselect.js"></script>
<fieldset>
<form method="post" action="" onsubmit="multipleSelectOnSubmit()">
<input type="hidden" name="cid" value="<?php print $cid; ?>" />
<select multiple name="fromBox[]" id="fromBox">
<?php
for ($i = 0; $i < count($users_diff); $i++):
?>
	<option value="<?php print $users_diff[$i]['User_ID']; ?>"><?php print (isset($users_diff[$i]['User_Fullname'])) ? $users_diff[$i]['User_Fullname'].' - '.$users_diff[$i]['User_Username']: $users_diff[$i]['User_Username']; ?></option>
<?php
endfor;
?>
</select>
<select multiple name="toBox[]" id="toBox">
<?php
for ($i = 0; $i < count($users); $i++):
?>
	<option value="<?php print $users[$i]['User_ID']; ?>"><?php print (isset($users[$i]['User_Fullname'])) ? $users[$i]['User_Fullname'].' - '.$users[$i]['User_Username']: $users[$i]['User_Username']; ?></option>
<?php
endfor;
?>
</select>
<input name="submit" type="submit" value="Update"><a class="button" href="/?p=course">Cancel</a>
</form>
</fieldset>
<script type="text/javascript">
createMovableOptions("fromBox","toBox",550,300,'Available <?php print $users_label; ?>','Selected <?php print $users_label; ?>');
</script>