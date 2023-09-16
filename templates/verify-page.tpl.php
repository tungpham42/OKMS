<?php //Email verification page template
if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
    // Verify data
    $email = htmlspecialchars($_GET['email']); // Set email variable
    $hash = htmlspecialchars($_GET['hash']); // Set hash variable

    $stmt = $db->link->prepare("SELECT User_Mail, User_Hash, User_Status FROM " . $db->db_prefix . "USER WHERE User_Mail=:email AND User_Hash=:hash AND User_Status='0'");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':hash', $hash, PDO::PARAM_STR);
    $stmt->execute();
    
    $match = $stmt->rowCount();

    if ($match > 0) {
        // We have a match, activate the account
        $updateStmt = $db->link->prepare("UPDATE " . $db->db_prefix . "USER SET User_Status='1' WHERE User_Mail=:email AND User_Hash=:hash AND User_Status='0'");
        $updateStmt->bindParam(':email', $email, PDO::PARAM_STR);
        $updateStmt->bindParam(':hash', $hash, PDO::PARAM_STR);
        $updateStmt->execute();
        
        echo '<h2>Your account has been activated, you can now <a href="/home">login</a></h2>';
    } else {
        // No match -> invalid URL or account has already been activated.
        echo '<h2>The URL is either invalid or you already have activated your account. Go to the login page <a href="/home">here</a></h2>';
    }
} else {
    // Invalid approach
    echo '<h2>Invalid approach, please use the link that has been sent to your email.</h2>';
}
?>