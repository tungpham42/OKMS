<?php
//header('Connection: keep-alive');
//header('Content-Encoding: gzip');
// error_reporting(-1);
// ini_set('display_errors', 'On');
require 'includes/functions.inc.php';
require 'includes/admin.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<?php include('templates/partials/head.tpl.php'); ?>
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
			<?php include('templates/partials/header.tpl.php'); ?>
			</div>
		</div>
	</div>
	<!-- End Header -->
	<div id="page_section" class="container-fluid">
		<!-- Start Main -->
		<div id="main" class="container-xxl px-0">
			<div class="row px-0 w-100">
				<!-- Start Side Bar -->
				<div id="leftmenu" class="col-xxl-2 col-xl-2 col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php include('templates/partials/leftmenu.tpl.php'); ?></div>
				<!-- End Side Bar -->			
				<div id="content" class="col-xxl-7 col-xl-7 col-lg-9 col-md-12 col-sm-12 col-xs-12">
					<div id="inner_content">
						<h1 class="title"><?php echo $title; ?></h1>
						<?php
						include('templates/views.tpl.php');
						?>
					</div>
				</div>
				<div id="rightmenu" class="col-xxl-3 col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12">
					<?php include('templates/partials/rightmenu.tpl.php'); ?>
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
		<?php include('templates/partials/footer.tpl.php'); ?>
	</div>
	<!-- End Footer -->
	<div id="overlay"></div>
	</body>
</html>