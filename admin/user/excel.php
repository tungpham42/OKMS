<?php
$username_starting_cell_column = (isset($_POST['username_starting_cell_column'])) ? $_POST['username_starting_cell_column']: '';
$username_starting_cell_row = (isset($_POST['username_starting_cell_row'])) ? $_POST['username_starting_cell_row']: '';
$password_starting_cell_column = (isset($_POST['password_starting_cell_column'])) ? $_POST['password_starting_cell_column']: '';
$password_starting_cell_row = (isset($_POST['password_starting_cell_row'])) ? $_POST['password_starting_cell_row']: '';
$email_starting_cell_column = (isset($_POST['email_starting_cell_column'])) ? $_POST['email_starting_cell_column']: '';
$email_starting_cell_row = (isset($_POST['email_starting_cell_row'])) ? $_POST['email_starting_cell_row']: '';
$role_starting_cell_column = (isset($_POST['role_starting_cell_column'])) ? $_POST['role_starting_cell_column']: '';
$role_starting_cell_row = (isset($_POST['role_starting_cell_row'])) ? $_POST['role_starting_cell_row']: '';
?>
<table width="600">
<form id="form" action="" method="post" enctype="multipart/form-data">
<tr><td colspan="3">Format: CSV File<br/>Fields: Username, Password, Email, Role ID</td></tr>
<tr>
<td>Username starting cell: </td>
<td><label for="username_starting_cell_column">Column: </label><input type="text" class="required" name="username_starting_cell_column" value="<?php echo $username_starting_cell_column; ?>"/></td>
<td><label for="username_starting_cell_row">Row: </label><input type="text" class="required" name="username_starting_cell_row" value="<?php echo $username_starting_cell_row; ?>"/></td>
</tr>
<tr>
<td>Password starting cell: </td>
<td><label for="password_starting_cell_column">Column: </label><input type="text" class="required" name="password_starting_cell_column" value="<?php echo $password_starting_cell_column; ?>"/></td>
<td><label for="password_starting_cell_row">Row: </label><input type="text" class="required" name="password_starting_cell_row" value="<?php echo $password_starting_cell_row; ?>"/></td>
</tr>
<tr>
<td>Email starting cell: </td>
<td><label for="email_starting_cell_column">Column: </label><input type="text" class="required" name="email_starting_cell_column" value="<?php echo $email_starting_cell_column; ?>"/></td>
<td><label for="email_starting_cell_row">Row: </label><input type="text" class="required" name="email_starting_cell_row" value="<?php echo $email_starting_cell_row; ?>"/></td>
</tr>
<tr>
<td>Role ID starting cell: </td>
<td><label for="role_starting_cell_column">Column: </label><input type="text" class="required" name="role_starting_cell_column" value="<?php echo $role_starting_cell_column; ?>"/></td>
<td><label for="role_starting_cell_row">Row: </label><input type="text" class="required" name="role_starting_cell_row" value="<?php echo $role_starting_cell_row; ?>"/></td>
</tr>
<tr>
<td width="20%">Select file</td>
<td width="30%"><input type="file" name="file" id="file" /></td>
<td width="50%">
Query: 
<select name="query">
	<option <?php echo ($_POST['query'] == 'none') ? 'selected ': ''; ?>value="none">None</option>
	<option <?php echo ($_POST['query'] == 'create') ? 'selected ': ''; ?>value="create">Execute the Create Query</option>
	<option <?php echo ($_POST['query'] == 'update_pass') ? 'selected ': ''; ?>value="update_pass">Execute the Update Password Query</option>
	<option <?php echo ($_POST['query'] == 'update_email') ? 'selected ': ''; ?>value="update_email">Execute the Update Email Query</option>
	<option <?php echo ($_POST['query'] == 'delete') ? 'selected ': ''; ?>value="delete">Execute the Delete Query</option>
</select>
</td>
</tr>
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
echo parse_excel_to_table("upload/" . $storagename);
$profiles = parse_excel_to_array("upload/" . $storagename);
$usernames = parse_excel_column_to_custom_array("upload/" . $storagename,$username_starting_cell_column,$username_starting_cell_row);
//$passwords = parse_excel_column_to_custom_array("upload/" . $storagename,$password_starting_cell_column,$password_starting_cell_row);
//$emails = parse_excel_column_to_custom_array("upload/" . $storagename,$email_starting_cell_column,$email_starting_cell_row);
//$roles = parse_excel_column_to_custom_array("upload/" . $storagename,$role_starting_cell_column,$role_starting_cell_row);
echo '<pre>';
print_r($usernames);
//print_r($passwords);
//print_r($emails);
//print_r($roles);
echo '</pre>';
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
echo count($profiles) . ' row' . $s;
echo (isset($_POST['query']) && $_POST['query'] == 'create') ? ' (CREATE)': '';
echo (isset($_POST['query']) && $_POST['query'] == 'update_pass') ? ' (UPDATE PASS)': '';
echo (isset($_POST['query']) && $_POST['query'] == 'update_email') ? ' (UPDATE EMAIL)': '';
echo (isset($_POST['query']) && $_POST['query'] == 'delete') ? ' (DELETE)': '';
echo (isset($_POST['query']) && $_POST['query'] != 'none') ? ' executed': ' not executed';
?>