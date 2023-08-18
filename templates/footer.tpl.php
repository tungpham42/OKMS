<?php //Footer template
if (!isset($_GET['p']) || $_GET['p'] == 'home'):
?>
<div id="inner_footer" class="col-12">
	<div id="footer_first" class="footer_column">
		<div class="footer_heading">Connect with us</div>
		<div class="footer_content">
			<a target="_blank" class="footer_icon facebook" href="https://www.facebook.com/groups/295960750500743"></a>
			<a class="footer_icon linkedin" href="#"></a>
			<a class="footer_icon skype" href="#"></a>
			<a class="footer_icon twitter" href="#"></a>
		</div>
	</div>
	<div class="footer_divider"></div>
	<div id="footer_second" class="footer_column">
		<div class="footer_heading">Project Inquiries</div>
		<div class="footer_content">
			RMIT International University<br/>
			702 Nguyen Van Linh Boulevard<br/>
			Tan Phong Ward, District 7,<br/>
			Ho Chi Minh City, Vietnam<br/><br/>
			Email: nelson.leung@rmit.edu.vn<br/>
			Contact Person: Nelson Leung<br/>
			Tel: (84-8)3776 1300 Ext 1410
		</div>
	</div>
	<div class="footer_divider"></div>
	<div id="footer_third" class="footer_column">
		<div class="footer_heading">About us</div>
		<div class="footer_content">
			The Online Knowledge Management System is a web-based application to aim for the desire of grasping the fundamental and advanced knowledge firmly of students. It has been developed by a group of Business Information Systems' students who are participating in Capstone project in last semester.<br/><br/>
			It has been served for academic purpose only.
		</div>
	</div>
</div>
<?php
endif;
?>
<?php
if (isset($_GET['p']) && $_GET['p'] != 'home'):
?>
<style>
#footer {min-height: 0px;}
#footer_bottom {top: 0px;}
</style>
<?php
endif;
?>
<div id="footer_bottom" class="w-100">
	<div id="inner_footer_bottom">
		<span style="margin: 0 auto; width: 50%; float: left;">Copyright 2012 by the Avengers Group, RMIT International University, Vietnam . All rights reserved</span>
	</div>
</div>