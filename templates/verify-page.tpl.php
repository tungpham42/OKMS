<?php //Email verification page template
if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
	// Verify data
	$email = mysql_escape_string($_GET['email']); // Set email variable
	$hash = mysql_escape_string($_GET['hash']); // Set hash variable
	$search = mysql_query("SELECT User_Mail, User_Hash, User_Status FROM ".PREFIX."USER WHERE User_Mail='".$email."' AND User_Hash='".$hash."' AND User_Status='0'") or die(mysql_error());
	$match  = mysql_num_rows($search);
	if($match > 0){
		// We have a match, activate the account
		mysql_query("UPDATE ".PREFIX."USER SET User_Status='1' WHERE User_Mail='".$email."' AND User_Hash='".$hash."' AND User_Status='0'") or die(mysql_error());  
		echo '<h2>Your account has been activated, you can now <a href="?p=home">login</a></h2>';
	}else{
		// No match -> invalid url or account has already been activated.
		echo '<h2>The url is either invalid or you already have activated your account. Go to login page <a href="?p=home">here</a></h2>';
	}
}else{
	// Invalid approach  
    echo '<h2>Invalid approach, please use the link that has been send to your email.</h2>';
}
?>