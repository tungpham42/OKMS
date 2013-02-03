<div id="login">
<h1>User login</h1>
<?php
if(!$_SESSION['username'])
{
?>
	<script type="text/javascript"> 
	function clickclear(thisfield, defaulttext) {
	if (thisfield.value == defaulttext) {
	thisfield.value = "";
	}
	}
	 
	function clickrecall(thisfield, defaulttext) {
	if (thisfield.value == "") {
	thisfield.value = defaulttext;
	}
	}
	</script> 
	<form method="post" action="">
		<table>
			<?php
			if(isset($_POST['login'])){
				if($err)
				print '<tr><td style="color: red" colspan="2">'.implode('<br />',$err).'</td></tr>';
			}
			?>
			<tr>
				<td><label for="username">Username:</label></td>
				<td><input type="text" name="username" title="Fill in your username" size="20" /></td>
			</tr>
			<tr>
				<td><label for="password">Password:</label></td>
				<td><input type="password" name="password" title="Fill in your password" size="20" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Login" name="login" class="bt_login"/></td>
			</tr>
		</table>
	</form>
<?php
}
if($_SESSION['username'])
{
	header('Location: '.$_SERVER['HTTP_REFERER'].'');
} 
?>

<p id="nav">
<a href="?p=user/create">Register</a> |
<a href="?p=user/password_reset" title="Password Lost and Found">Lost your password?</a>
</p>
</div>
<div class="clear"></div>