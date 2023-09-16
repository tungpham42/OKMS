<?php //Create post
require_once '../includes/functions.inc.php';
require_once '../includes/admin.inc.php';
$uid = $_SESSION['uid'];
$cid = (isset($_POST['cid'])) ? $_POST['cid']: '';
$week = (isset($_POST['week'])) ? $_POST['week']: '';
$title = (isset($_POST['title'])) ? $_POST['title']: '';
$url = (isset($_POST['url'])) ? $_POST['url']: '';
$body = (isset($_POST['body'])) ? $_POST['body']: '';
$answer = (isset($_POST['answer'])) ? $_POST['answer']: '';
$hide = (isset($_POST['hide'])) ? $_POST['hide']: 0;
$url = htmlspecialchars($url); // Sanitize the URL
// Check if the URL exists in the database
$stmt = $db->link->prepare("SELECT * FROM " . $db->db_prefix . "POST WHERE Post_URL=:url");
$stmt->bindParam(':url', $url, PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount() == 0) {
    // URL is available, create the post
    create_post($uid, $cid, $week, $title, $url, $body, $answer, $hide);
    echo 'URL_AVAILABLE';
} else {
    // URL already exists
    echo 'URL_EXISTS';
}
?>