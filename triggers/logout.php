<?php //Logout
session_name('okms');
session_start();
session_unset();
session_destroy();
header('Location: /');
?>