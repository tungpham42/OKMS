<?php
// Password reset page template
if (isset($_POST['submit'])) {
    if ($_POST['forgotpassword'] == '') {
        error('Please Fill in Email.');
    }
    $forgotpassword = htmlspecialchars(stripslashes($_POST['forgotpassword']));
    
    // Make sure it's a valid email address
    if (!filter_var($_POST['forgotpassword'], FILTER_VALIDATE_EMAIL)) {
        error('Email Not Valid - Must be in the format of name@domain.tld');
    }
    
    // Check if the email exists
    $stmt = $db->link->prepare("SELECT User_Mail, User_Hash FROM " . $db->db_prefix . "USER WHERE User_Mail = :email");
    $stmt->bindParam(':email', $forgotpassword, PDO::PARAM_STR);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
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
                <p>Online KMS received a request to reset the password for your account</p>
                <p>Please click <a href="'.currentURL().'/user/password_reset&email='.$forgotpassword.'&hash='.$hash.'">here</a> to reset your password.</p>
                <p>If you did not want to reset your password, please ignore this email.</p>
            </td>
        </tr>
    </table>';
    
    if (filter_var($_POST['forgotpassword'], FILTER_VALIDATE_EMAIL) && $stmt->rowCount() > 0) {
        send_mail($forgotpassword, $subject, $message, 'tung.42@gmail.com');
        echo '
        <table>
            <tr><th style="text-transform: none; text-align: left;">Notice from the system</th></tr>
            <tr><td>
            <p>Hi. An email has been dispatched to <span style="font-weight: bold;position:relative;top:-2px;">'.$forgotpassword.'</span> with a confirmation link to reset your password.</p>
            <p>Thanks and best regards</p>
            </td></tr>
        </table>';
    }
}

if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
    // Verify data
    $email = htmlspecialchars($_GET['email']); // Set email variable
    $hash = htmlspecialchars($_GET['hash']); // Set hash variable

    // Generate a RANDOM MD5 Hash for a password
    $random_password = md5(uniqid(rand()));
    
    // Take the first 8 digits and use them as the password we intend to email the user
    $emailpassword = substr($random_password, 0, 8);
    
    // Encrypt $emailpassword in MD5 format for the database
    $newpassword = md5($emailpassword);
    
    // Check if the email and hash match
    $stmt = $db->link->prepare("SELECT User_Mail, User_Hash FROM " . $db->db_prefix . "USER WHERE User_Mail = :email AND User_Hash = :hash");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
    $stmt->execute();
    
    $match = $stmt->rowCount();
    
    if ($match > 0) {
        // Update the password in the database
        $updateStmt = $db->link->prepare("UPDATE " . $db->db_prefix . "USER SET User_Password = :newpassword WHERE User_Mail = :email");
        $updateStmt->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $updateStmt->execute();

        // Email the new password to the user
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
        
        send_mail($email, $subject, $message, 'tung.42@gmail.com');
        
        echo '
        <table>
            <tr><th style="text-transform: none; text-align: left;">Notice from the system</th></tr>
            <tr><td>
            <p>Hi. An email has been dispatched to <span style="font-weight: bold;position:relative;top:-2px;">'.$email.'</span> with details of your new password.</p>
            <p>Please click <a style="position:relative;top:-2px;text-decoration:underline;" href="/home">here</a> to go to the home page.</p>
            <p>Thanks and best regards</p>
            </td></tr>
        </table>';
    } else {
        // No match -> invalid URL
        echo '<h2>The URL is invalid</h2>';
    }
}

if (!isset($_GET['email']) || empty($_GET['email']) || !isset($_GET['hash']) || empty($_GET['hash'])) {
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
<?php
}
?>