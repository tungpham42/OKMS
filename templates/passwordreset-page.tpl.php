<?php //Password reset page template
if (isset($_POST['submit'])) {	
	if ($_POST['forgotpassword']=='') {
		error('Please Fill in Email.');
	}
	if(get_magic_quotes_gpc()) {
		$forgotpassword = htmlspecialchars(stripslashes($_POST['forgotpassword']));
	} 
	else {
		$forgotpassword = htmlspecialchars($_POST['forgotpassword']);
	}
	//Make sure it's a valid email address, last thing we want is some sort of exploit!
	if (!check_email_address($_POST['forgotpassword'])) {
  		error('Email Not Valid - Must be in format of name@domain.tld');
	}
    // Lets see if the email exists
    $sql = "SELECT COUNT(*) FROM ".PREFIX."USER WHERE User_Mail = '$forgotpassword'";
    $result = mysqli_query($db->link, $sql)or die('Could not find member: ' . mysqli_error());
    if (!mysqli_result($result,0,0)>0) {
        error('Email Not Found!');
    }
	$user = user_load_from_mail($forgotpassword);
	$fullname = $user['User_Fullname'];
	$hash = $user['User_Hash'];
	$subject = 'Online KMS - Password reset confirmation';
	$message = '
	<table style="border: 1px solid black;">
		<tr style="border: 1px solid black;">
			<td>
				<img src="'.currentURL().'/images/banner_email.png" width="480" height="80" />
			</td>
		</tr>
		<tr style="border: 1px solid black;">
			<td>
				<p>Hi <b>'.$fullname.'</b></p>
				<p>Online KMS received a request to reset password for your account</p>
				<p>Please click <a href="'.currentURL().'/user/password_reset&email='.$forgotpassword.'&hash='.$hash.'">here</a> to reset your password.</p>
				<p>If you did not want to reset your password, please ignore this email.</p>
			</td>
		</tr>
	</table>';
	if (check_email_address($_POST['forgotpassword']) && mysqli_result($result,0,0)>0):
		send_mail($forgotpassword, $subject, $message,  'okms.vn@gmail.com');
		echo '
			<table>
				<tr><th style="text-transform: none; text-align: left;">Notice from the system</th></tr>
				<tr><td>
				<p>Hi. An email has been dispatched to <span style="font-weight: bold;position:relative;top:-2px;">'.$forgotpassword.'</span> with confirmation link to reset your password.</p>
				<p>Thanks and best regards</p>
				</td></tr>
			</table>';
	endif;
}
if(isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])){
	// Verify data
	$email = mysqli_escape_string($db->link, $_GET['email']); // Set email variable
	$hash = mysqli_escape_string($db->link, $_GET['hash']); // Set hash variable

	//Generate a RANDOM MD5 Hash for a password
	$random_password=md5(uniqid(rand()));
	
	//Take the first 8 digits and use them as the password we intend to email the user
	$emailpassword=substr($random_password, 0, 8);
	
	//Encrypt $emailpassword in MD5 format for the database
	$newpassword = md5($emailpassword);
	
	$search = mysqli_query($db->link, "SELECT User_Mail, User_Hash FROM ".PREFIX."USER WHERE User_Mail='".$email."' AND User_Hash='".$hash."'") or die(mysqli_error());
	$match  = mysqli_num_rows($search);
	if($match > 0){
		// Make a safe query
		$query = sprintf("UPDATE `".PREFIX."USER` SET `User_Password` = '%s' 
						  WHERE `User_Mail` = '$email'",
						mysqli_real_escape_string($db->link, $newpassword));
		mysqli_query($db->link, $query)or die('Could not update members: ' . mysqli_error());

		//Email out the infromation
		$subject = 'Online KMS - Your New Password';
		$message = '
		<table style="border: 1px solid black;">
			<tr style="border: 1px solid black;">
				<td>
					<img src="'.currentURL().'/images/banner_email.png" width="480" height="80" />
				</td>
			</tr>
			<tr style="border: 1px solid black;">
				<td>
					<p>Your new password is as follows:</p>
					<p>Password: '.$emailpassword.'</p>
					<p>Please make note this information has been encrypted into our database.</p>
					<p>This email was automatically generated.</p>
				</td>
			</tr>
		</table>';
		send_mail($email, $subject, $message,  'okms.vn@gmail.com');
		echo '
			<table>
				<tr><th style="text-transform: none; text-align: left;">Notice from the system</th></tr>
				<tr><td>
				<p>Hi. An email has been dispatched to <span style="font-weight: bold;position:relative;top:-2px;">'.$email.'</span> with details of your new password. </p>
				<p>Please click <a style="position:relative;top:-2px;text-decoration:underline;" href="/home">here</a> to go to home page.</p>
				<p>Thanks and best regards</p>
				</td></tr>
			</table>';
	}else{
		// No match -> invalid url or account has already been activated.
		echo '<h2>The url is invalid</h2>';
	}
}
if(!isset($_GET['email']) || empty($_GET['email']) || !isset($_GET['hash']) || empty($_GET['hash'])){
?>
<form id="form" name="forgotpasswordform" action="" method="post">
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
		<tr>
			<td><label for="forgotpassword">Email Address:</label></td>
            <td><input name="forgotpassword" type="text" value="" id="forgotpassword" size="30" maxlength="128" class="required email" /></td>
		</tr>
		<tr>
			<td></td>
            <td><input type="submit" name="submit" value="Submit" class="mainoption" /></td>
		</tr>
	</table>
</form>
<?
}
?>
