<?php //Delete comment
require_once '../includes/functions.inc.php';
$comid = (isset($_POST['comid'])) ? $_POST['comid']: ''; 
delete_comment($comid);
?>