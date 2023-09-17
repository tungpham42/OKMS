<?php
if (isset($_POST['submit'])) {
    if (empty($_POST['forgotpassword'])) {
        error('Please Fill in Email.');
    }
    
    $forgotpassword = $_POST['forgotpassword'];
    
    // Make sure it's a valid email address
    if (!filter_var($forgotpassword, FILTER_VALIDATE_EMAIL)) {
        error('Email Not Valid - Must be in the format of name@domain.tld');
    }
    
    // Let's see if the email exists
    $stmt = $db->link->prepare("SELECT COUNT(*) FROM ".PREFIX."USER WHERE User_Mail = :email");
    $stmt->bindParam(':email', $forgotpassword, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    
    if ($result == 0) {
        error('Email Not Found!');
    }
    
    // Generate a RANDOM MD5 Hash for a password
    $random_password = md5(uniqid(rand()));
    
    // Take the first 8 digits and use them as the password we intend to email the user
    $emailpassword = substr($random_password, 0, 8);
    
    // Encrypt $emailpassword in MD5 format for the database
    $newpassword = md5($emailpassword);
    
    // Make a safe query
    $stmt = $db->link->prepare("UPDATE ".PREFIX."USER SET User_Password = :newpassword WHERE User_Mail = :email");
    $stmt->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $stmt->bindParam(':email', $forgotpassword, PDO::PARAM_STR);
    $stmt->execute();
    
    // Email out the information
    $subject = "Your New Password";
    $message = "Your new password is as follows:
    ----------------------------
    Password: $emailpassword
    ----------------------------
    Please make note this information has been encrypted into our database.

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