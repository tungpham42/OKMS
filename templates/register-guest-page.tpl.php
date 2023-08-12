<?php //Register page template
$name = (isset($_POST['name'])) ? $_POST['name']: '';
$fullname = (isset($_POST['fullname'])) ? $_POST['fullname']: '';
$alias = (isset($_POST['alias'])) ? $_POST['alias']: '';
$pass = (isset($_POST['pass'])) ? $_POST['pass']: '';
$pass1 = (isset($_POST['pass1'])) ? $_POST['pass1']: '';
$mail = (isset($_POST['mail'])) ? $_POST['mail']: '';
$rid = 2;
$has_agreed = (isset($_POST['has_agreed'])) ? $_POST['has_agreed']: 0;
$hash = md5(rand(0,1000));
$err = auth_guest_error_array($name,$fullname,$pass,$mail,$rid,$pass1,$has_agreed);
if (isset($_POST['submit']) && $pass == $pass1):
	if (!count($err)):
		create_user($rid,$name,$fullname,$alias,$pass,$mail,$hash);
		send_mail(	$mail,
					'Online KMS Registration System - Your New Account',
					'
<table style="border: 1px solid black;">
	<tr style="border: 1px solid black;">
		<td>
			<img src="'.currentURL().'/images/banner_email.png" width="480" height="80" />
		</td>
	</tr>
	<tr style="border: 1px solid black;">
		<td>
			<p>Hi <b>'.$fullname.'</b></p>
			<p>You have just registered an account on Online Knowledge Management system</p>
			<p>Your ID: '.$name.'</p>
			<p>Your Password: '.substr($pass,0,3).'***</p>
			<p>Please click <a href="'.currentURL().'/user/verify&email='.$mail.'&hash='.$hash.'">here</a> to activate your account now.</p>
		</td>
	</tr>
</table>
',
					'tung.42@gmail.com');
		print '
		<table>
			<tr><th style="text-transform: none; text-align: left;">Notice from the system</th></tr>
			<tr><td>
			<p>Thank you for registering, <span style="font-weight: bold;position:relative;top:-2px;">'.$fullname.'</span>. An email has been dispatched to <span style="font-weight: bold;position:relative;top:-2px;">'.$mail.'</span> with details on how to activate your account. </p>
			<p>You MUST follow the link in that email before you can post on these forums. Until you do that, you will be told that you do not have permission to post.</p>
			<p>Please click <a style="position:relative;top:-2px;text-decoration:underline;" href="/home">here</a> to go to home page.</p>
			<p>Thanks and best regards</p>
			</td></tr>
		</table>';
		//sleep(5);
		//header('location: '.currentURL().'/home');
	else:
		$old_name = (isset($_POST['name'])) ? $_POST['name']: '';
		$old_fullname = (isset($_POST['fullname'])) ? $_POST['fullname']: '';
		$old_alias = (isset($_POST['alias'])) ? $_POST['alias']: '';
		$old_mail = (isset($_POST['mail'])) ? $_POST['mail']: '';
		$old_rid = (isset($_POST['rid'])) ? $_POST['rid']: 2;		
	endif;
endif;
?>
<?php
if ((isset($_POST['submit']) && count($err) > 0) || !isset($_POST['submit'])):
?>
<form id="form" method="post" action="">
	<table>
		<?php
		if(isset($_POST['submit'])){
			if($err)
			print '<tr><td style="color: red" colspan="2">'.implode('<br />',$err).'</td></tr>';
		}
		?>
		<tr>
			<td><label for="name">Username:</label></td>
			<td><input id="name" type="text" name="name" size="30" maxlength="128" class="required" value="<?php print $old_name; ?>" /><br/><div style="display: none" id="username_check"></div><span id="username_check_label"></span></td>
		</tr>
		<tr>
			<td width="30%"><label for="mail">Email:</label></td>
			<td><input id="mail" type="text" name="mail" size="30" maxlength="128" class="required" value="<?php print $old_mail; ?>" /><br/><div style="display: none" id="email_check"></div><span id="email_check_label"></span></td>
		</tr>
		<tr>
			<td><label for="fullname">Fullname:</label></td>
			<td><input type="text" name="fullname" size="30" maxlength="128" class="required" value="<?php print $old_fullname; ?>" /></td>
		</tr>
		<tr>
			<td><label for="pass">Password:</label></td>
			<td><input type="password" name="pass" size="30" maxlength="128" class="required" /></td>
		</tr>
		<tr>
			<td><label for="pass1">Retype Password:</label></td>
			<td><input type="password" name="pass1" size="30" maxlength="128" class="required" /><span style="display: none;" id="check_pass"></span></td>
		</tr>
		<tr>
			<td colspan="2">
				<input name="has_agreed" type="checkbox" value="1" />
				<label style="position: relative;top: -4px;" for="has_agreed">I have read, and agree to abide by the <a style="position: relative;top: -2px;" target="_blank" href="/terms-and-conditions">Terms and Conditions</a></label>
			</td>
		</tr>
		<tr>
			<td><input onclick="checkPass();" type="submit" name="submit" value="Register" /></td>
			<td><input type="reset" value="Reset" /></td>
		</tr>
	</table>
</form>
<?php
	if ($_SESSION['rid'] != 1):
?>
<script type="text/javascript">
function checkPass() {
	if ($("input[name=pass]").val() != $("input[name=pass1]").val()) {
		$("span#check_pass").css("display","block");
		$("span#check_pass").text("Password not match").css("color","red");
		return false;
	} else {
		$("span#check_pass").css("display","none");
		$("span#check_pass").text("");
		return true;
	}
}
function checkUsername() {
	var username = $("input#name").val();
	$("#username_check").load("/triggers/username_check.php",{username:username},function(data){
		if (data == "USERNAME_EXISTS") {
			$("span#username_check_label").css("color","red").text("Username exists");
		} else if ($("input#name").val() != "" && data == "USERNAME_AVAILABLE") {
			$("span#username_check_label").css("color","green").text("Username is available");
		} else if ($("input#name").val() == "") {
			$("span#username_check_label").css("color","red").text("Username is empty");
		}
	});
}
function checkEmail() {
	var email = $("input#mail").val();
	$("#email_check").load("/triggers/email_check.php",{email:email},function(data){
		if (data == "EMAIL_EXISTS") {
			$("span#email_check_label").css("color","red").text("Email exists");
		} else if ($("input#mail").val() != "" && data == "EMAIL_AVAILABLE") {
			$("span#email_check_label").css("color","green").text("Email is available");
		}
	});
}
$("input#name").on('keyup change input', checkUsername);
$("input#mail").on('keyup change input', checkEmail);
</script>
<?php
	endif;
elseif (isset($_POST['submit']) && count($err) == 0):
	print '';
endif;
?>
