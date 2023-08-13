<?php //Register page template
$name = (isset($_POST['name'])) ? $_POST['name']: '';
$fullname = (isset($_POST['fullname'])) ? $_POST['fullname']: '';
$alias = (isset($_POST['alias'])) ? $_POST['alias']: '';
$pass = (isset($_POST['pass'])) ? $_POST['pass']: '';
$pass1 = (isset($_POST['pass1'])) ? $_POST['pass1']: '';
$mail = (isset($_POST['mail'])) ? $_POST['mail']: '';
$rid = (isset($_POST['rid'])) ? $_POST['rid']: '';
$has_agreed = (isset($_POST['has_agreed'])) ? $_POST['has_agreed']: 0;
$hash = md5(rand(0,1000));
$err = auth_error_array($name,$fullname,$pass,$mail,$rid,$pass1,$has_agreed);
if (isset($_POST['submit']) && $pass == $pass1):
	if (!count($err)):
		create_user($rid,$name,$fullname,$alias,$pass,$mail,$hash);
		if(mysqli_affected_rows($link)==1)
		{
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
			echo '
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
		}
		else
		{
			echo '<span style="color: red;">This username or email is already taken!</span>';
			$old_name = (isset($_POST['name'])) ? $_POST['name']: '';
			$old_fullname = (isset($_POST['fullname'])) ? $_POST['fullname']: '';
			$old_alias = (isset($_POST['alias'])) ? $_POST['alias']: '';
			$old_mail = (isset($_POST['mail'])) ? $_POST['mail']: '';
			$old_rid = (isset($_POST['rid'])) ? $_POST['rid']: null;		
		}
	else:
		$old_name = (isset($_POST['name'])) ? $_POST['name']: '';
		$old_fullname = (isset($_POST['fullname'])) ? $_POST['fullname']: '';
		$old_alias = (isset($_POST['alias'])) ? $_POST['alias']: '';
		$old_mail = (isset($_POST['mail'])) ? $_POST['mail']: '';
		$old_rid = (isset($_POST['rid'])) ? $_POST['rid']: null;		
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
			echo '<tr><td style="color: red" colspan="2">'.implode('<br />',$err).'</td></tr>';
		}
		?>
		<tr>
			<td><label for="name">RMIT ID:</label></td>
			<td><input id="name" type="text" name="name" size="30" maxlength="128" class="required" value="<?php echo $old_name; ?>" /><br/><div style="display: none" id="username_check"></div><span id="username_check_label"></span><br/><span id="id_hint" style="display: none; color: #BE1E2D;">(Vxxxxx for Staff, Sxxxxxxx or Lxxxxxxx for Students)</span></td>
		</tr>
		<tr>
			<td width="30%"><label for="mail">Email:</label></td>
			<td><input id="mail" type="text" name="mail" size="30" maxlength="128" class="required email" value="<?php echo $old_mail; ?>" /><input id="alias" type="hidden" name="alias" size="30" maxlength="128" /></td>
		</tr>
		<tr>
			<td><label for="fullname">Fullname:</label></td>
			<td><input type="text" name="fullname" size="30" maxlength="128" class="required" value="<?php echo $old_fullname; ?>" /></td>
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
			<td><label for="rid">Role:</label></td>
			<td><?php if ($_SESSION['rid'] == 1): ?><?php echo select_role('rid',$old_rid); ?><?php else: ?><?php echo radio_role('role',$old_rid); ?><input id="rid" type="hidden" name="rid" value="<?php echo $old_rid; ?>" /><?php endif; ?></td>
		</tr>
		<tr>
			<td colspan="2">
				<input name="has_agreed" type="checkbox" value="1" />
				<label style="position: relative;top: -4px;" for="has_agreed">I have read, and agree to abide by the <a style="position: relative;top: -2px;" target="_blank" href="/terms-and-conditions">Terms and Conditions</a></label>
			</td>
		</tr>
		<tr>
			<td><input onclick="checkPass();" type="submit" name="submit" value="Submit" /></td>
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
		$("span#check_pass").text("Password not match");
		return false;
	} else {
		$("span#check_pass").css("display","none");
		$("span#check_pass").text("");
		return true;
	}
}
$().ready(function(){
	$("input:radio[name=role]").attr("disabled",true);
});
function updateField(){
	var email = $("input#mail").val();
	var pos = email.indexOf("@");
	var length = email.length;
	var name = $("input#name").val();
	var domain = email.substr(pos+1);
	var first_letter = name.substr(0,1);
	var alias = email.substr(0,pos);
	//$("input#name_display").val(name);
	//$("input#name").val(name);
	if (first_letter == "s" || first_letter == "S" || first_letter == "l" || first_letter == "L") {
		$("input:radio[name=role]")[0].checked = true;
		$("input#rid").val("2");
		$("tr#user_alias").css("display","none");
	} else if (first_letter == "v" || first_letter == "V" || first_letter == "e" || first_letter == "E") {
		$("input:radio[name=role]")[1].checked = true;
		$("input#rid").val("3");
	} else {
		$("input:radio[name=role]").attr("checked",false);
		$("input#rid").val("");
		$("tr#user_alias").css("display","none");
	}
}
function updateAlias() {
	var email = $("input#mail").val();
	var pos = email.indexOf("@");
	var alias = email.substr(0,pos);
	var length = email.length;
	var name = $("input#name").val();
	var domain = email.substr(pos+1);
	var first_letter = name.substr(0,1);
	if (first_letter == "v" || first_letter == "V" || first_letter == "e" || first_letter == "E") {
		$("input#alias").val(alias);
	}
}
function checkUsername() {
	var username = $("input#name").val();
	$("#username_check").load("/triggers/username_check.php",{username:username},function(data){
		if (data == "USERNAME_EXISTS") {
			$("span#username_check_label").css("color","red").text("Username Exists");
		} else if ($("input#name").val() != "" && data == "USERNAME_AVAILABLE") {
			$("span#username_check_label").css("color","green").text("Username Available");
		} else if ($("input#name").val() == "") {
			$("span#username_check_label").css("color","red").text("Username Empty");
		}
	});
}
$("input#name").keyup(checkUsername).keydown(checkUsername).change(checkUsername);
$("input#name").keyup(updateField).keydown(updateField).change(updateField);
$("input#mail").keyup(updateAlias).keydown(updateAlias).change(updateAlias);
$("input#name").focus(function(){
	$("span#id_hint").css("display","block");
}).focusout(function(){
	$("span#id_hint").css("display","none");
});
</script>
<?php
	endif;
elseif (isset($_POST['submit']) && count($err) == 0):
	echo '';
endif;
?>
