<?php
//header('Connection: keep-alive');
//header('Content-Encoding: gzip');
// error_reporting(-1);
// ini_set('display_errors', 'On');
require 'includes/functions.inc.php';
require 'includes/admin.inc.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php echo $meta_description; ?>">
		<meta name="keywords" content="kms,cms,online_kms,knowledge management system">
		<meta name="author" content="Tung Pham">
		<meta property="og:url" content="<?php echo currentURL(); ?>">
		<meta property="og:type" content="website">
		<meta property="og:locale" content="en_US">
		<meta property="og:title" content="<?php echo $site_name.(($p != 'home' && $p != '') ? ' - ': ' ').$title; ?>">
		<meta property="og:description" content="<?php echo $meta_description; ?>">
		<meta property="og:image" content="<?php echo currentURL(); ?>/images/okms_200x200.png">
		<meta property="og:image:type" content="image/png">
		<meta property="og:image:width" content="200">
		<meta property="og:image:height" content="200">
		<title><?php echo $site_name.(($p != 'home' && $p != '') ? ' - ': ' ').$title; ?></title>
		<link rel="stylesheet" href="/css/style_<?php echo (isset($_SESSION['theme'])) ? $_SESSION['theme']: 'default'; ?>.css" type="text/css" media="screen">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.1/css/bootstrap-grid.min.css" integrity="sha512-2cWcZ9cbPMZFm2inlFOhwsBVbNMmNxKBtVXqL8OY9tXCZahhnIfXMxPCzpKqiHF2I2mOiNHNXEDUDglwd+4uYw==" crossorigin="anonymous" referrerpolicy="no-referrer">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.1/css/bootstrap-utilities.min.css" integrity="sha512-cfwnOJPyV+wKMunF+AeiFo+CJX+AN3xF+DyT7TQ0P9/RjcVM9ZlRFoN6m6ZjU+avqGKL8w8hKFXkdjSk8CDl0A==" crossorigin="anonymous" referrerpolicy="no-referrer">
		<!--[if IE]>
		<link type="text/css" rel="stylesheet" media="all" href="/css/ie.css">
		<![endif]-->
		<script>
		if (navigator.appName == 'Microsoft Internet Explorer') {
			document.write('<link type="text/css" rel="stylesheet" media="all" href="/css/ie.css">');
		}
		</script>
		<!--<script src="http://yui.yahooapis.com/3.6.0/build/yui/yui-min.js"></script>-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/yui/3.18.1/yui/yui-min.js" integrity="sha512-xRL1U0vZqWAcR3uFMaar+fYTEA3spq+rHdDnlV/xQIj3nhrvRrLTvvWwQLjcyuvTy8eCNwFQEIavOjbCLythRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<link rel="shortcut icon" href="/images/favicon.ico">
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZETS4QXH8Z"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-ZETS4QXH8Z');
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
	<script type="text/javascript" src="/js/jquery.js"></script>
-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js" integrity="sha512-J9QfbPuFlqGD2CYVCa6zn8/7PEgZnGpM5qtFOBZgwujjDnG5w5Fjx46YzqvIh/ORstcj7luStvvIHkisQi5SKw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdn.jsdelivr.net/npm/transliteration@2.3.5/dist/browser/bundle.umd.min.js" integrity="sha256-WM+Q7gs+YPKhWaTZxr24xQ9DF8yT7m2WJdrKYBVdGh4=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="/js/animation.js"></script>
	<script type="text/javascript" src="/js/scripts.js"></script>
	<script type="text/javascript" src="/js/jquery.qtip.min.js"></script>
	<!-- Start Header -->
	<div id="header" class="container-fluid fixed-top shadow-lg">
		<div id="inner_header" class="container-xxl pe-0">
			<div class="col-12">
			<?php include('templates/header.tpl.php'); ?>
			</div>
		</div>
	</div>
	<!-- End Header -->
	<div id="page_section" class="container-fluid">
		<!-- Start Main -->
		<div id="main" class="container-xxl px-0">
			<div class="row px-0 w-100">
				<!-- Start Side Bar -->
				<div id="leftmenu" class="col-xxl-2 col-xl-2 col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php include('templates/leftmenu.tpl.php'); ?></div>
				<!-- End Side Bar -->			
				<div id="content" class="col-xxl-7 col-xl-7 col-lg-9 col-md-12 col-sm-12 col-xs-12">
					<div id="inner_content">
						<h1 class="title"><?php echo $title; ?></h1>
						<?php
						if (!isset($_SESSION['rid']) && isset($_GET['p']) && $_GET['p'] == 'user/create'):
							include('templates/register-page.tpl.php');
						elseif (!isset($_SESSION['rid']) && isset($_GET['p']) && $_GET['p'] == 'user/register'):
							include('templates/register-guest-page.tpl.php');
						elseif (!isset($_SESSION['rid']) && isset($_GET['p']) && $_GET['p'] == 'user/password_reset'):
							include('templates/passwordreset-page.tpl.php');
						elseif (!isset($_SESSION['rid']) && isset($_GET['p']) && $_GET['p'] == 'user/verify'):
							include('templates/verify-page.tpl.php');
						elseif (!isset($_GET['p']) || $_GET['p'] == 'home'):
							include('templates/front.tpl.php');
						elseif ($p == 'search'):
							include('templates/search.tpl.php');
						elseif ($p == 'option'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1):
								include('admin/option/index.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || $_SESSION['rid'] == 3):
								include('admin/course/index.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course/create'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/course/create.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course/edit'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || ($_SESSION['rid'] == 3 && $course['User_ID'] == $_SESSION['uid'])):
								include('admin/course/edit.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course/delete'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/course/delete.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course/assign'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 || ($_SESSION['rid'] == 3 && $course['User_ID'] == $_SESSION['uid'])):
								include('admin/course/assign.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course/enrol'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 1):
								include('admin/course/enrol.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course/promote'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/course/promote.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course/csv'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] == 3):
								include('admin/course/csv.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'course/excel'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] == 3):
								include('admin/course/excel.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'menu'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/menu/index.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'menu/create'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/menu/create.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'menu/edit'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/menu/edit.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'menu/delete'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/menu/delete.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'post'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
								include('admin/post/index.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'post/create'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
								include('admin/post/create.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'post/edit'):
							if (isset($_SESSION['rid'])):
								include('admin/post/edit.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'post/delete'):
							if (isset($_SESSION['rid'])):
								include('admin/post/delete.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'post/archive'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
								include('admin/post/archive.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'report'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2):
								include('templates/report.tpl.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'role'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/role/index.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'role/create'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/role/create.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'role/edit'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/role/edit.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'role/delete'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/role/delete.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'semester'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/semester/index.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'semester/create'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/semester/create.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'semester/edit'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/semester/edit.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'semester/delete'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/semester/delete.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'type'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/type/index.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'type/create'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/type/create.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'type/edit'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/type/edit.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'type/delete'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/type/delete.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'user'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/user/index.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'user/create'):
							if (!isset($_SESSION['rid']) || isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/user/create.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'user/edit'):
							if (isset($_SESSION['rid'])):
								include('admin/user/edit.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'user/delete'):
							if (isset($_SESSION['rid'])):
								include('admin/user/delete.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'user/csv'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/user/csv.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif ($p == 'user/excel'):
							if (isset($_SESSION['rid']) && $_SESSION['rid'] != 2 && $_SESSION['rid'] != 3):
								include('admin/user/excel.php');
							else:
								echo 'You are not authorized';
							endif;
						elseif (isset($_SESSION['rid']) && $p == 'user/password_reset'):
							echo 'You are not authorized';
						elseif (isset($_SESSION['rid']) && $p == 'user/verify'):
							echo 'You are not authorized';
						elseif (in_array($p,$user_paths)):
							$user = user_load($uid);
							if (isset($_SESSION['username']) && $_SESSION['username'] == $user['User_Username'] || $_SESSION['rid'] == 1):
								include('admin/user/account.php');
							else:
								echo 'You are not authorized';
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
							echo 'Page not found';
						endif;
						?>
					</div>
				</div>
				<div id="rightmenu" class="col-xxl-3 col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12">
					<?php include('templates/rightmenu.tpl.php'); ?>
				</div>
			</div>
		<script type="text/javascript" src="/js/jquery.validate.js"></script>
		<script type="text/javascript">
		var windowHeight = $(window).height();
		$("body.admin #content").css("height",(windowHeight - 160) + "px");
		$('#form').validate();
		$("a.button.disabled").click(function(){
			openLogin();
		});
		$('[title]').qtip({
			style: {
				padding: 8,
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
	<div id="footer" class="container-fluid">
		<?php include('templates/footer.tpl.php'); ?>
	</div>
	<!-- End Footer -->
	<div id="overlay"></div>
	</body>
</html>