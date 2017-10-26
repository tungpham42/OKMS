<?php
$name = (isset($_POST['name'])) ? $_POST['name']: '';
$fullname = (isset($_POST['fullname'])) ? $_POST['fullname']: '';
$pass = (isset($_POST['pass'])) ? $_POST['pass']: '';
$pass1 = (isset($_POST['pass1'])) ? $_POST['pass1']: '';
$mail = (isset($_POST['mail'])) ? $_POST['mail']: '';
$rid = (isset($_POST['rid'])) ? $_POST['rid']: '';
$hash = md5(rand(0,1000));
$err = pass_error($pass);
if (isset($_POST['submit']) && $pass == $pass1):
	if (!count($err)):
		create_user($rid,$name,$fullname,'',$pass,$mail,$hash);
		send_mail(	$mail,
					'Online KMS Registration System - Your New Account',
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
			<p>You have just registered an account on Online Knowledge Management system</p>
			<p>Your ID: '.$name.'</p>
			<p>Your Password: '.substr($pass,0,3).'***</p>
			<p>Please click <a href="'.currentURL().'?p=user/verify&email='.$mail.'&hash='.$hash.'">here</a> to activate your account now.</p>
		</td>
	</tr>
</table>
',
					'okms.vn@gmail.com');
			//sleep(1);
			//header('location: '.currentURL().'?p=home');
			print '<br/>User created. Click <a href="/?p=home">here</a> to go to home page<br/>';
			print 'We sent you an email with your new account information!';
	else:
		print implode('<br />',$err);
	endif;
endif;
?>
<form id="form" method="post" action="">
	<table>
		<tr>
			<td><label for="mail">Email:</label></td>
			<td><input id="mail" type="text" name="mail" size="60" maxlength="128" class="required email" /></td>
		</tr>
		<tr>
			<td><label for="name">Username:</label></td>
			<td><input id="name" type="text" name="name" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="fullname">Fullname:</label></td>
			<td><input type="text" name="fullname" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="pass">Password:</label></td>
			<td><input type="password" name="pass" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="pass1">Retype Password:</label></td>
			<td><input type="password" name="pass1" size="60" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="rid">Role:</label></td>
			<td><?php if ($_SESSION['rid'] == 1): ?><?php print select_role('rid'); ?><?php else: ?><?php print radio_role('role'); ?><input id="rid" type="hidden" name="rid" /><?php endif; ?></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit" value="Submit" /></td>
			<td><input type="reset" value="Reset" /></td>
		</tr>
	</table>
</form>
<?php
if ($_SESSION['rid'] != 1):
?>
<script type="text/javascript">
$().ready(function(){
	$('input:radio[name=role]').attr('disabled',true);
});
function update_field(){
	var email = $('input#mail').val();
	var pos = email.indexOf('@');
	var length = email.length;
	var name = email.substr(0,pos);
	var domain = email.substr(pos+1);
	var first_letter = name.substr(0,1);
	$('input#name_display').val(name);
	$('input#name').val(name);
	if (first_letter == 's') {
		$('input:radio[name=role]')[0].checked = true;
		$('input#rid').val('2');
	} else if (first_letter == 'v') {
		$('input:radio[name=role]')[1].checked = true;
		$('input#rid').val('3');
	} else {
		$('input:radio[name=role]').attr('checked',false);
	}
}
$('input#mail').keyup(update_field).keydown(update_field);
</script>
<?php
endif;
?>
