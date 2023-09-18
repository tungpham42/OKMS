<?php
/* Auth */
global $db;
session_name('okms');
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    // If not isset -> set with dummy value
    $_SESSION['username'] = null;
}
$err = array();
if(isset($_POST['header_login']) || isset($_POST['wrap_login'])){
	// Will hold our errors
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	if(!count($err))
	{
		// Escape and prepare the input data
		$username = $_POST['username'];
		$password = $_POST['password'];
		$rememberMe = (int)$_POST['rememberMe'];

		// Hash the password
		$passwordHash = md5($password);

		// Prepare and execute the query
		$query = "SELECT User_ID, User_Username, Role_ID, User_Status, User_Alias FROM " . PREFIX . "USER WHERE (User_Username = :username OR User_Alias = :username) AND User_Password = :password";
		$stmt = $db->link->prepare($query);
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);
		$stmt->execute();

		// Fetch the result
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if((isset($row['User_Username']) || isset($row['User_Alias'])) && $row['User_Status'] == 1)
		{
			// If everything is OK login
			$_SESSION['rid'] = $row['Role_ID'];
			$_SESSION['username'] = $row['User_Username'];
			$_SESSION['uid'] = $row['User_ID'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			// Store some data in the session
			setcookie('okmsRemember',$_POST['rememberMe']);
			if (isset($_POST['header_login'])) {
				header('Location: '.$_SERVER['HTTP_REFERER']);
			} elseif (isset($_POST['wrap_login'])) {
				header('Location: '.$_SERVER['HTTP_REFERER']);
			}
		}
		elseif(isset($row['User_Username']) && $row['User_Status'] == 0)
		{
			$err[]='User not confirmed email yet';
		}
		elseif(!isset($row['User_Username']))
		{
			$err[]='Wrong username and/or password!';
		}
	}
}
if (!username_existed($_SESSION['username'])) {
	// session_name('okms');
	// session_unset();
	session_destroy();
} elseif (username_existed($_SESSION['username'])) {
	// session_name('okms');
	// session_start();
}
/* End Auth */
require('routes.inc.php');