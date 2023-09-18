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
elseif ($p == 'help'):
    include('templates/help.tpl.php');
else:
    echo 'Page not found';
endif;