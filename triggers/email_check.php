<?php //Create post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$email = (isset($_POST['email'])) ? $_POST['email']: '';
$emailCheck = $db->link->prepare("SELECT * FROM {$db->db_prefix}USER WHERE User_Mail = :email");
$emailCheck->bindParam(':email', $email, PDO::PARAM_STR);
$emailCheck->execute();
if ($emailCheck->rowCount() == 0) {
    if (check_mail($email)) {
        echo 'EMAIL_AVAILABLE';
    }
} else {
    echo 'EMAIL_EXISTS';
}
?>