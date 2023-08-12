<table width="600">
<form action="" method="post" enctype="multipart/form-data">
<tr><td colspan="3">Format: CSV File<br/>Fields: Username, Password, Email, Role ID</td></tr>
<tr>
<td width="20%">Select file</td>
<td width="30%"><input type="file" name="file" id="file" /></td>
<td width="50%">
Query: 
<select name="query">
	<option <?php print ($_POST['query'] == 'none') ? 'selected ': ''; ?>value="none">None</option>
	<option <?php print ($_POST['query'] == 'create') ? 'selected ': ''; ?>value="create">Execute the Create Query</option>
	<option <?php print ($_POST['query'] == 'update_pass') ? 'selected ': ''; ?>value="update_pass">Execute the Update Password Query</option>
	<option <?php print ($_POST['query'] == 'update_email') ? 'selected ': ''; ?>value="update_email">Execute the Update Email Query</option>
	<option <?php print ($_POST['query'] == 'delete') ? 'selected ': ''; ?>value="delete">Execute the Delete Query</option>
</select>
</td>

<tr>
<td><input type="submit" name="submit" /></td>
<td colspan="3"><a class="button" href="/user">Cancel</a></td>
</tr>

</form>
</table>
<?php
if ( isset($_POST["submit"]) ) {

	if ( isset($_FILES["file"])) {

            //if there was an error uploading the file
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

        }
        else {
                 //Print file details
             echo "Upload: " . $_FILES["file"]["name"] . "<br />";
             echo "Type: " . $_FILES["file"]["type"] . "<br />";
             echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
             echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                 //if file already exists
             if (file_exists("upload/" . $_FILES["file"]["name"])) {
            echo $_FILES["file"]["name"] . " already exists. ";
             }
             else {
                    //Store file in directory "upload" with the name of "uploaded_file.txt"
            $storagename = "uploaded_file.txt";
            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
            echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
            }
        }
	} else {
		echo "No file selected <br />";
	}
}
$profiles = array();
$row = 0;
if (($handle = fopen("upload/" . $storagename , "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 3000, ",")) !== FALSE) {
        $row++;
		$profiles[] = $data;
    }
    fclose($handle);
}

for ($i = 0; $i < count($profiles); $i++):
	if (isset($_POST['query']) && $_POST['query'] == 'update_pass'):
		$pass = md5($profiles[$i][1]);
		csv_update_pass($profiles[$i][0],$pass);
	endif;
	if (isset($_POST['query']) && $_POST['query'] == 'update_email'):
		csv_update_pass($profiles[$i][0],$profiles[$i][2]);
	endif;
	if (isset($_POST['query']) && $_POST['query'] == 'create'):
		csv_create_user($profiles[$i][0],$profiles[$i][1],$profiles[$i][2]);
	endif;
	if (isset($_POST['query']) && $_POST['query'] == 'delete'):
		$user = user_load_from_name($profiles[$i][0]);
		$uid = $user['uid'];
		delete_user($uid);
	endif;
endfor;
$s = (count($profiles) == 0 || count($profiles) == 1) ? '': 's';
print count($profiles) . ' row' . $s;
print (isset($_POST['query']) && $_POST['query'] == 'create') ? ' (CREATE)': '';
print (isset($_POST['query']) && $_POST['query'] == 'update_pass') ? ' (UPDATE PASS)': '';
print (isset($_POST['query']) && $_POST['query'] == 'update_email') ? ' (UPDATE EMAIL)': '';
print (isset($_POST['query']) && $_POST['query'] == 'delete') ? ' (DELETE)': '';
print (isset($_POST['query']) && $_POST['query'] != 'none') ? ' executed': ' not executed';
print '<pre>';
print_r($profiles);
print '</pre>';
?>