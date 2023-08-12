<?php
$cid = (isset($_POST['cid'])) ? $_POST['cid']: 0;
?>
<table width="500">
<form action="" method="post" enctype="multipart/form-data">
<tr><td colspan="2">Format: CSV file<br/>Field: Username</td></tr>
<tr>
<td width="20%">Select file</td>
<td width="30%"><input type="file" name="file" id="file" /></td>


<tr>
<td><input type="submit" name="submit" /></td>
<td colspan="3"><a class="button" href="/course">Cancel</a></td>
</tr>
<input type="hidden" name="cid" value="<?php print $cid; ?>" />
</form>
</table>
<?php
if ( isset($_POST["submit"]) ) {
	if ( isset($_FILES["file"])) {
        if ($_FILES["file"]["error"] > 0) {
        }
        else {
			//if file already exists
			if (file_exists("upload/" . $_FILES["file"]["name"])) {
				echo $_FILES["file"]["name"] . " already exists. ";
				}
			else {
				//Store file in directory "upload" with the name of "uploaded_file.txt"
				$storagename = "uploaded_file.xls";
				move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
				print 'Import successfully!';
            }
        }
	} else {
		echo "No file selected <br />";
	}
}
print parse_excel_to_table("upload/" . $storagename);
$profiles = csv_to_array("upload/" . $storagename);
for ($i = 0; $i < count($profiles); $i++):
	$username = $profiles[$i];
	$user = user_load_from_name($username);
	$uid = $user['User_ID'];
	if ($user['Role_ID'] == 2):
		delete_course_user($cid,$uid);
		create_course_user($cid,$uid);
	endif;
endfor;
?>