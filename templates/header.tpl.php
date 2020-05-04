<div style="display: none" id="wrap">
	<div id="wrap-border">
	<a class="close-button" onclick="closeWrapBox()"></a>
		<div id="wrap-content">
		</div>
	</div>
</div>
<div style="display: none" id="loading">
  <p><img src="./images/ajax-loader.gif" /> Please Wait</p>
</div>
<div id="banner">
	<a href="." id="logo" name="logo"></a>
</div>
<div class="right">
	<?php include('templates/login-block.tpl.php'); ?>
</div>
<?php
if (isset($_SESSION['rid']) && $_SESSION['rid'] == 1):
?>
<div id="toolbar">
<style type="text/css">
body.admin.post #toolbar ul.toolbar > li > a.admin.post,body.admin.user #toolbar ul.toolbar > li > a.admin.user,body.admin.role #toolbar ul.toolbar > li > a.admin.role,body.admin.course #toolbar ul.toolbar > li > a.admin.course,body.admin.semester #toolbar ul.toolbar > li > a.admin.semester,body.admin.archive #toolbar ul.toolbar > li > a.admin.archive, body.front #toolbar ul.toolbar > li > a.front, body.option #toolbar ul.toolbar > li > a.option, body.report #toolbar ul.toolbar > li > a.report
{
	text-decoration: none;
	color: #404041;
	background: #F4F5F5;
	font-weight: bold;
	padding: 2px 10px 6px;
}
#toolbar {width: 780px;}
</style>
<ul class="toolbar">
<li><a class="admin post" title="Manage posts" href="?p=post">Manage posts</a></li>
<li><a class="admin user" title="Manage users" href="?p=user">Manage users</a></li>
<li><a class="admin role" title="Manage roles" href="?p=role">Manage roles</a></li>
<li><a class="admin course" title="Manage courses" href="?p=course">Manage courses</a></li>
<li><a class="admin semester" title="Manage semesters" href="?p=semester">Manage semesters</a></li>
<li><a class="admin archive" title="Knowledge base" href="?p=post/archive">Knowledge base</a></li>
<li><a class="admin report" title="Report" href="?p=report">Report</a></li>
</ul>
</div>
<?php
elseif (isset($_SESSION['rid']) && $_SESSION['rid'] == 3):
?>
<div id="toolbar">
<style type="text/css">
body.admin.post #toolbar ul.toolbar > li > a.admin.post, body.admin.course #toolbar ul.toolbar > li > a.admin.course, body.front #toolbar ul.toolbar > li > a.front, body.report #toolbar ul.toolbar > li > a.report,body.admin.archive #toolbar ul.toolbar > li > a.admin.archive {
	text-decoration: none;
	color: #404041;
	background: #F4F5F5;
	font-weight: bold;
	padding: 2px 10px 6px;
}
#toolbar {width: 510px;}
</style>
<ul class="toolbar">
<li><a class="admin post" title="Manage posts" href="?p=post">Manage posts</a></li>
<li><a class="admin course" title="Manage courses" href="?p=course">Manage courses</a></li>
<li><a class="admin archive" title="Knowledge base" href="?p=post/archive">Knowledge base</a></li>
<li><a class="admin report" title="Report" href="?p=report">Report</a></li>
</ul>
</div>
<?php
endif;
?>
<div class="clear"></div>