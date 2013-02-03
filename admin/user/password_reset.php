<?php
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
    $result = mysql_query($sql)or die('Could not find member: ' . mysql_error());
    if (!mysql_result($result,0,0)>0) {
        error('Email Not Found!');
    }

	//Generate a RANDOM MD5 Hash for a password
	$random_password=md5(uniqid(rand()));
	
	//Take the first 8 digits and use them as the password we intend to email the user
	$emailpassword=substr($random_password, 0, 8);
	
	//Encrypt $emailpassword in MD5 format for the database
	$newpassword = md5($emailpassword);
	
	// Make a safe query
	$query = sprintf("UPDATE `".PREFIX."USER` SET `User_Password` = '%s' 
					  WHERE `User_Mail` = '$forgotpassword'",
                    mysql_real_escape_string($newpassword));
	mysql_query($query)or die('Could not update members: ' . mysql_error());

	//Email out the infromation
$subject = "Your New Password"; 
$message = "Your new password is as follows:
---------------------------- 
Password: $emailpassword
---------------------------- 
Please make note this information has been encrypted into our database 

This email was automatically generated.";
}
?>
<form name="forgotpasswordform" action="" method="post">
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
		<tr>
			<td><label for="forgotpassword">Email Address:</label></td>
            <td><input name="forgotpassword" type="text" value="" id="forgotpassword" size="60" maxlength="128" /></td>
		</tr>
		<tr>
			<td></td>
            <td><input type="submit" name="submit" value="Submit" class="mainoption" /></td>
		</tr>
	</table>
</form>