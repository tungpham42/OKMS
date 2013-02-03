<?php
require_once '../includes/functions.inc';
$comid = (isset($_POST['comid'])) ? $_POST['comid']: ''; 
$body = (isset($_POST['body'])) ? $_POST['body']: ''; 
edit_comment($comid,$body);
?>