<?php
$user = user_load($_SESSION['uid']);
$uid = isset($_POST['uid']) ? $_POST['uid']: '';
$old_name = isset($_POST['old_name']) ? $_POST['old_name']: $user['User_Username'];
$old_fullname = isset($_POST['old_fullname']) ? $_POST['old_fullname']: $user['User_Fullname'];
$old_mail = isset($_POST['old_mail']) ? $_POST['old_mail']: $user['User_Mail'];
$old_rid = isset($_POST['old_rid']) ? $_POST['old_rid']: $user['Role_ID'];
$rid = isset($_POST['rid']) ? $_POST['rid']: $old_rid;
$name = isset($_POST['name']) ? $_POST['name']: '';
$fullname = isset($_POST['fullname']) ? $_POST['fullname']: '';
$current_pass = isset($_POST['current_pass']) ? $_POST['current_pass']: '';
$pass = isset($_POST['pass']) ? $_POST['pass']: '';
$pass1 = isset($_POST['pass1']) ? $_POST['pass1']: '';
$mail = isset($_POST['mail']) ? $_POST['mail']: '';
$err = ($current_pass == '' && $pass == '') ? null: pass_error_array($uid,$current_pass,$pass,$pass1);
if (isset($_POST['submit'])):
	if (!count($err)){
		if (!isset($pass) || $pass == '' && $current_pass == ''):
			edit_user_without_pass($_POST['uid'],$rid,$fullname);
		elseif (isset($pass) && $pass != ''):
			edit_user($_POST['uid'],$rid,$fullname,$pass);
		endif;
		if($pass != '')
		{
			send_mail(	$mail,
						'Online KMS Registration System - Your Account get changed',
						'
<table style="border: 1px solid black;">
	<tr style="border: 1px solid black;">
		<td>
			<img src="'.currentURL().'images/banner_email.png" width="480" height="80" />
		</td>
	</tr>
	<tr style="border: 1px solid black;">
		<td>
			<p>Hi <b>'.$fullname.'</b></p>
			<p>Your new password is: '.substr($pass,0,3).'***</p>
		</td>
	</tr>
</table>',
						'okms.vn@gmail.com');
			print '<br/>User edited. Click <a href="/home">here</a> to go to home page<br/>';
			print 'We sent you an email with your changed account information!';
		}
		sleep(3);
		header('location: '.currentURL().'/home');
		//else print 'This username or email is already taken!';
	} else {
		print '<span style="color: red;">'.implode('<br />',$err).'<br/></span>';
		$old_name = isset($_POST['name']) ? $_POST['name']: $user['User_Username'];
		$old_fullname = isset($_POST['fullname']) ? $_POST['fullname']: $user['User_Fullname'];
		$old_mail = isset($_POST['mail']) ? $_POST['mail']: $user['User_Mail'];
		$old_rid = isset($_POST['rid']) ? $_POST['rid']: $user['Role_ID'];
	}
endif;
?>
<form id="form" method="post" action="">
	<input type="hidden" name="uid" value="<?php print $uid; ?>"/>
	<table>
		<tr>
			<td><label for="name">Username:</label></td>
			<td><?php print $old_name; ?><input type="hidden" name="name" value="<?php print $old_name; ?>"/></td>
		</tr>
		<tr>
			<td><label for="fullname">Fullname:</label></td>
			<td><input type="text" name="fullname" value="<?php print $old_fullname; ?>" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="current_pass">Current Password:</label></td>
			<td><input type="password" name="current_pass" size="60" maxlength="128" /></td>
		</tr>
		<tr>
			<td><label for="pass">New Password:</label></td>
			<td><input type="password" name="pass" size="60" maxlength="128" /></td>
		</tr>
		<tr>
			<td><label for="pass1">Retype New Password:</label></td>
			<td><input type="password" name="pass1" size="60" maxlength="128" /><span id="check_pass"></span></td>
		</tr>
		<tr>
			<td><label for="mail">Email:</label></td>
			<td><?php print $old_mail; ?><input type="hidden" name="mail" value="<?php print $old_mail; ?>" /></td>
		</tr>
		<tr>
			<td><label for="rid">Role:</label></td>
			<td><?php if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1) {print select_role('rid',$old_rid);} elseif (isset($_SESSION['rid']) && $_SESSION['rid'] != 1) { print load_name_from_rid($old_rid); }?></td>
		</tr>
		<tr>
			<td>Belonged courses:</td>
			<td><?php print view_user_courses($uid); ?></td>
		</tr>
		<tr>
			<td><input onclick="checkPass();" type="submit" name="submit" value="Save" /></td>
			<td><a class="button" href="/user">Cancel</a></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
function checkPass() {
	if ($("input[name=pass]").val() != $("input[name=pass1]").val()) {
		$("span#check_pass").css("display","block");
		$("span#check_pass").text("Password not match");
		return false;
	} else {
		$("span#check_pass").css("display","none");
		$("span#check_pass").text("");
		return true;
	}
}
</script>
