<?php //Create post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$username = (isset($_POST['username'])) ? $_POST['username']: '';
$usernameCheck = $db->link->prepare("SELECT * FROM {$db->db_prefix}USER WHERE User_Username = :username");
$usernameCheck->bindParam(':username', $username, PDO::PARAM_STR);
$usernameCheck->execute();

if ($usernameCheck->rowCount() == 0) {
    echo 'USERNAME_AVAILABLE';
} else {
    echo 'USERNAME_EXISTS';
}
?>