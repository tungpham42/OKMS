<?php
//header('Connection: keep-alive');
//header('Content-Encoding: gzip');
//error_reporting(-1);
//ini_set('display_errors', 'On');
require 'includes/functions.inc.php';
require 'includes/admin.inc.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		<meta name="description" content="<?php print $meta_description; ?>" />
		<meta name="keywords" content="kms,cms,online_kms,knowledge management system" />
		<meta name="author" content="Tung Pham" />
		<title><?php print $site_name.(($p != 'home' && $p != '') ? ' - ': ' ').$title; ?></title>
		<link rel="stylesheet" href="css/style_<?php print (isset($_SESSION['theme'])) ? $_SESSION['theme']: 'default'; ?>.css" type="text/css" media="screen" />
		<!--[if IE]>
		<link type="text/css" rel="stylesheet" media="all" href="css/ie.css" />
		<![endif]-->
		<script>
		if (navigator.appName == 'Microsoft Internet Explorer') {
			document.write('<link type="text/css" rel="stylesheet" media="all" href="css/ie.css" />');
		}
		</script>
		<!--<script src="http://yui.yahooapis.com/3.6.0/build/yui/yui-min.js"></script>-->
		<script type="text/javascript" src="js/yui/yui/yui-min.js"></script>
		<link rel="shortcut icon" href="images/favicon.ico" />
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-108831421-1"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-108831421-1');
		</script>
	</head>
	<body class="<?php echo $body_class; ?>">
	<noscript>
		<style>
		#header {top: 37px;}
		#page_section {top: 194px;}
		.noscriptmsg {
			position: fixed;
			top: 0px;
			border: 0px solid white;
			width: 100%;
			background: #FFF9D7;
			color: #333;
			padding: 10px;
			line-height: 16px;
			font-size: 13px;
			font-weight: bold;
			margin: 0 auto;
		}
		.noscriptmsg > div {
			margin: auto;
			width: 540px;
		}
		</style>
		<div class="noscriptmsg">
			<div>For a better experience on KMS Online, enable JavaScript in your browser</div>
		</div>
	</noscript>
<!--
	<script type="text/javascript" src="js/jquery.js"></script>
-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
	<script type="text/javascript" src="js/animation.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<script type="text/javascript" src="js/jquery.qtip.min.js"></script>
	<!-- Start Header -->
	<div id="header">
		<div id="inner_header">
			<?php include('templates/header.tpl.php'); ?>
		</div>
	</div>
	<!-- End Header -->
	<div id="page_section">
		<!-- Start Main -->
		<div id="main">
			<!-- Start Side Bar -->
			<div id="leftmenu"><?php include('templates/leftmenu.tpl.php'); ?></div>
			<!-- End Side Bar -->			
			<div id="content">
				<div id="inner_content">
					<h1 class="title"><?php print $title; ?></h1>
					<?php
					if (!isset($_SESSION['rid']) && $_GET['p'] == 'user/create'):
						include('templates/register-page.tpl.php');
					elseif (!isset($_SESSION['rid']) && $_GET['p'] == 'user/register'):
						include('templates/register-guest-page.tpl.php');
					elseif (!isset($_SESSION['rid']) && $_GET['p'] == 'user/password_reset'):
						include('templates/passwordreset-page.tpl.php');
					elseif (!isset($_SESSION['rid']) && $_GET['p'] == 'user/verify'):
						include('templates/verify-page.tpl.php');
					elseif (!isset($_GET['p']) || $_GET['p'] == 'home'):
						include('templates/front.tpl.php');
					elseif ($p == 'search'):
						include('templates/search.tpl.php');
					elseif ($p == 'option'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1):
							include('admin/option/index.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || $_SESSION['rid'] == 3):
							include('admin/course/index.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course/create'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/course/create.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course/edit'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || ($_SESSION['rid'] == 3 && $course['User_ID'] == $_SESSION['uid'])):
							include('admin/course/edit.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course/delete'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/course/delete.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course/assign'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || ($_SESSION['rid'] == 3 && $course['User_ID'] == $_SESSION['uid'])):
							include('admin/course/assign.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course/enrol'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 1):
							include('admin/course/enrol.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course/promote'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/course/promote.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course/csv'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] == 3):
							include('admin/course/csv.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'course/excel'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] == 3):
							include('admin/course/excel.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'menu'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/menu/index.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'menu/create'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/menu/create.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'menu/edit'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/menu/edit.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'menu/delete'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/menu/delete.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'post'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
							include('admin/post/index.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'post/create'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
							include('admin/post/create.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'post/edit'):
						if (isset($_SESSION['rid'])):
							include('admin/post/edit.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'post/delete'):
						if (isset($_SESSION['rid'])):
							include('admin/post/delete.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'post/archive'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
							include('admin/post/archive.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'report'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
							include('templates/report.tpl.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'role'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/role/index.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'role/create'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/role/create.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'role/edit'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/role/edit.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'role/delete'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/role/delete.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'semester'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/semester/index.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'semester/create'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/semester/create.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'semester/edit'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/semester/edit.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'semester/delete'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/semester/delete.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'type'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/type/index.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'type/create'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/type/create.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'type/edit'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/type/edit.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'type/delete'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/type/delete.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'user'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/user/index.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'user/create'):
						if (!isset($_SESSION['rid']) || isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/user/create.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'user/edit'):
						if (isset($_SESSION['rid'])):
							include('admin/user/edit.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'user/delete'):
						if (isset($_SESSION['rid'])):
							include('admin/user/delete.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'user/csv'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/user/csv.php');
						else:
							print 'You are not authorized';
						endif;
					elseif ($p == 'user/excel'):
						if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
							include('admin/user/excel.php');
						else:
							print 'You are not authorized';
						endif;
					elseif (isset($_SESSION['rid']) && $p == 'user/password_reset'):
						print 'You are not authorized';
					elseif (isset($_SESSION['rid']) && $p == 'user/verify'):
						print 'You are not authorized';
					elseif (in_array($p,$user_paths)):
						$user = user_load($uid);
						if (isset($_SESSION['username']) && $_SESSION['username'] == $user['User_Username'] || $_SESSION['rid'] == 1):
							include('admin/user/account.php');
						else:
							print 'You are not authorized';
						endif;
					elseif (in_array($p,$profile_paths)):
						include('templates/profile.tpl.php');
					elseif (in_array($p,$profile_follow_paths)):
						include('templates/profile_follow.tpl.php');
					elseif (in_array($p,$post_paths)):
						include('templates/post.tpl.php');
					elseif (in_array($p,$week_paths)):
						include('templates/week.tpl.php');
					elseif (in_array($p,$course_paths)):
						include('templates/course.tpl.php');
					elseif (in_array($p,$course_week_paths)):
						include('templates/course-week.tpl.php');
					elseif ($p == 'sitemap'):
						include('templates/sitemap.tpl.php');
					elseif ($p == 'terms-and-conditions'):
						include('templates/terms.tpl.php');
					else:
						print 'Page not found';
					endif;
					?>
				</div>
			</div>
			<div id="rightmenu">
				<?php include('templates/rightmenu.tpl.php'); ?>
			</div>
		<script type="text/javascript" src="js/jquery.validate.js"></script>
		<script type="text/javascript">
		var windowHeight = $(window).height();
		$("body.admin #content").css("height",(windowHeight - 160) + "px");
		$('#form').validate();
		$("a.button.disabled").click(function(){
			openLogin();
		});
		$('[title]').qtip({
			style: {
				padding: 7,
				background: '#404041',
				color: 'white',
				fontSize: '10px',
				textAlign: 'center',
				border: {
					width: 2,
					color: 'white'
				},
				tip: 'topLeft',
				name: 'dark' // Inherit the rest of the attributes from the preset dark style
			},
			position: {
				corner: {
				target: 'bottomMiddle',
				tooltip: 'topLeft'
				}
			}
		});
		$("#loading").ajaxStart(function(){
			$(this).show();
		}).ajaxStop(function(){
			$(this).hide();
		});
		</script>
		<div class="clear"></div>
		</div>
		<!-- End Main -->
	</div>
	<!-- Start Footer -->
	<div id="footer">
	<?php include('templates/footer.tpl.php'); ?>
	</div>
	<!-- End Footer -->
	<div id="overlay"></div>
	</body>
</html>