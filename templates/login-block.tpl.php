<?php
if(!isset($_SESSION['username']))
{
?>
<div id="header-login-form">
	<form method="post" action="">
		<div class="username">
			<label for="username">USERNAME</label>
			<input type="text" name="username" title="Fill in your username" size="20" />
		</div>
		<div class="password">
			<label for="password">PASSWORD</label>
			<input type="password" name="password" title="Fill in your password" size="20" />
		</div>
		<div class="login-submit">
			<button type="submit" value="Login" name="header_login" class="login-button"></button>
		</div>
		<div class="remember">
			<input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me
		</div>
		<div class="login-bottom">
			<a class="forgot" href="/user/password_reset">Forgot Password</a> | <a class="register" href="/user/register">Sign Up</a>
		</div>
	</form>
</div>
<?php
if(isset($_POST['header_login'])){
	if($err) {
?>
	<script type="text/javascript">
	$(document).ready(function(){
		openWrap('<?php echo implode('<br />',$err); ?>');
	});
	</script>
<?php
	}
}
?>
<!--<a class="button" href="#" onclick="openLogin();return false">Login</a>-->
<div id="login-wrap">
	<div id="login-border">
	<a class="close-button" onclick="closeLoginBox()"></a>
		<div id="login">
		<h1>User login</h1>
		<?php
		if(!isset($_SESSION['username']))
		{
		?>
			<form method="post" action="">
				<table>
					<?php
					if(isset($_POST['wrap_login'])){
						if($err) {
							echo '<tr><td style="color: red" colspan="2">'.implode('<br />',$err).'</td></tr>';
					?>
						<script type="text/javascript">
						$(document).ready(function(){
							openLogin();
							$('#login-border').css('height','223px');
						});
						</script>
					<?php
						}
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
						<td><input type="submit" value="Login" name="wrap_login" class="bt_login"/></td>
					</tr>
				</table>
			</form>
		<?php
		}
		if(isset($_SESSION['username']))
		{
			header('Location: '.$_SERVER['HTTP_REFERER'].'');
		} 
		?>

		<p id="nav">
		<a href="<?php echo currentURL(); ?>/user/register">Register</a> |
		<a href="<?php echo currentURL(); ?>/user/password_reset" title="Password Lost and Found">Lost your password?</a>
		</p>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php
}
if (!isset($_SESSION['username'])) 
{
//If not isset -> set with dumy value 
$_SESSION['username'] = null; 
}
if($_SESSION['username'])
{
	$user = user_load($_SESSION['uid']);
	$email = $user['User_Mail'];
	$size = 24;
	$default = DEFAULT_AVATAR;
?>
	<div id="header-login-form" class="logged_in">
		<a class="avatar" href="/user/<?php echo $_SESSION['uid']; ?>"><img src="<?php echo "https://0.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=identicon&s=".$size; ?>" width="24px" /></a>
		<a class="username" href="/user/<?php echo $_SESSION['uid']; ?>"><?php echo (isset($user['User_Fullname'])) ? $user['User_Fullname']: $user['User_Username']; ?></a>
		<a class="front" href=".">Home</a>
		<span id="user-toggle"></span>
		<div id="user-panel">
			<ul>
				<?php
				if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1):
				?>
				<li><a class="admin post" title="Manage posts" href="/post">Manage posts</a></li>
				<li><a class="admin user" title="Manage users" href="/user">Manage users</a></li>
				<li><a class="admin role" title="Manage roles" href="/role">Manage roles</a></li>
				<li><a class="admin course" title="Manage courses" href="/course">Manage courses</a></li>
				<li><a class="admin semester" title="Manage semesters" href="/semester">Manage semesters</a></li>
				<li><a class="admin archive" title="Knowledge base" href="/post/archive">Knowledge base</a></li>
				<li><a class="admin report" title="Report" href="/report">Report</a></li>
				<?php
				elseif (isset($_SESSION['rid']) && $_SESSION['rid'] == 3):
				?>
				<li><a class="admin post" title="Manage posts" href="/post">Manage posts</a></li>
				<li><a class="admin course" title="Manage courses" href="/course">Manage courses</a></li>
				<li><a class="admin archive" title="Knowledge base" href="/post/archive">Knowledge base</a></li>
				<li><a class="admin report" title="Report" href="/report">Report</a></li>
				<?php
				endif;
				?>
				<li><a href="#">Help</a></li>
				<li><a href="/triggers/logout.php">Logout</a></li>
			</ul>
		</div>
	</div>
	<script type="text/javascript">
	$("#user-toggle").click(function(){
		if ($("#user-panel").css("display") == "none") {
			$("#user-toggle").addClass("clicked");
			$("#user-panel").css("display","block");
		} else if ($("#user-panel").css("display") == "block") {
			$("#user-toggle").removeClass("clicked");
			$("#user-panel").css("display","none");
		}
	});
	</script>
<?php
}
?>