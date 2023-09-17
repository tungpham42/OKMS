<?php
if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
    // Verify data
    $email = $_GET['email']; // Set email variable
    $hash = $_GET['hash']; // Set hash variable

    // Prepare and execute a SELECT statement
    $stmt = $db->link->prepare("SELECT User_Mail, User_Hash, User_Status FROM ".PREFIX."USER WHERE User_Mail=:email AND User_Hash=:hash AND User_Status='0'");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the results
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // We have a match, activate the account
        $stmt = $db->link->prepare("UPDATE ".PREFIX."USER SET User_Status='1' WHERE User_Mail=:email AND User_Hash=:hash AND User_Status='0'");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
        $stmt->execute();

        echo '<h2>Your account has been activated, you can now login</h2>';
    } else {
        // No match -> invalid URL or account has already been activated.
        echo '<h2>The URL is either invalid or you have already activated your account.</h2>';
    }
} else {
    // Invalid approach
    echo '<h2>Invalid approach, please use the link that has been sent to your email.</h2>';
}
?>