<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />

{meta}

<link rel="stylesheet" type="text/css" href="{path_site}css/core.css"/>
<link rel="stylesheet" type="text/css" href="{path_site}css/style.css"/>
<link rel="stylesheet" type="text/css" href="{path_site}css/boxes.css"/>
<link rel="stylesheet" type="text/css" href="{path_site}css/jquery.rating.css"/>
<link rel="stylesheet" type="text/css" href="{path_site}css/sexy_buttons.css"/>
<link rel="stylesheet" type="text/css" href="{path_site}css/jquery.modal.css"/>
<link rel="stylesheet" type="text/css" href="{path_site}css/invalid.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo get_theme_path(); ?>style.css" />

<script type="text/javascript" src="{path_site}js/main.js"></script>
<script type="text/javascript" src="{path_site}js/jquery/jquery.1.4.2.pack.js" ></script>
<script type="text/javascript" src="{path_site}js/jquery/jquery.scrollTo-min.js" ></script>
<script type="text/javascript" src="{path_site}js/jquery/jquery.tweet.js" ></script>
<script type="text/javascript" src="{path_site}js/jquery/jquery.class.star_rating.js" ></script>
<script type="text/javascript" src="{path_site}js/jquery/jquery.rating.js" ></script>
<script type="text/javascript" src="{path_site}js/jquery/jquery.modal.js" ></script>
<script type="text/javascript" src="{path_site}js/jquery/jquery.core.js" ></script>
<script type="text/javascript" src="{path_site}js/jquery/jquery.custom.js" ></script>

<script type="text/javascript" src="{path_site}js/cufon/cufon-yui.js" ></script>
<script type="text/javascript" src="{path_site}js/cufon/century_gothic_700.font.js" ></script>
<script type="text/javascript">
	Cufon.replace('h2');
	Cufon.replace('h3:not(.nocufon)');
	Cufon.replace('.search_n_cart_box label');
	Cufon.replace('#main_nav a');
</script>	

<?php do_action('head'); ?>

<!--[if IE 6]>
<script type="text/javascript" src="{path_site}js/jq-png-min.js"></script>
<script type="text/javascript" src="{path_site}js/png.js"></script>
<![endif]-->

</head>
<body class="{body_class} <?php body_classes(); ?>">

<div id="header-container">
    <div id="header">
        <div class="left_box"><a href="{logo_href}"><img src="http://www.mommade.it.php5-18.dfw1-1.websitetestlink.com/logo.png" alt="" id="logo"/></a></div>
        <div class="right_details">
            <div class="top_contents">
                <div class="sss_box"><h2><span>shop + share + socialize<small>sm</small></span></h2></div>
                <div class="search_box">
                <form action="{path_site}search" method="get" id="search_form">
	                <input type="text" name="query" id="query" />
	                <input type="submit" value="search" id="submit_search" />
	                <span></span>
	                <a href="{path_site}cart.php"><img src="{http_theme}images/icon_cart.jpg"></a>
                </form>
                </div>
                 
                 <div class="header_navigation">
                 	<a class="icon_home" href="{path_site}"><img src="{http_theme}images/icon_home.jpg"></a>
                 	
                 	<div class="nav_link nav_home"><a href="{path_site}">HOME</a></div>
                 	<div class="nav_link "><a href="{path_site}">VENDOR</a></div>
                 	<div class="nav_link "><a href="<?php echo Router::url(array('controller' => 'categories', 'action' => 'services')); ?>">SERVICES</a></div>
                 	<div class="nav_link "><a href="{path_site}about/">ABOUT US</a></div>
                 	<div class="nav_link nav_last"><a href="{path_site}wiki/">WIKI</a></div>
                 	
                 	<div class="navigation_right">
                 	<span>
                 		<?php if (is_user_logged_in()): ?>
                 		<a href="<?php echo Router::url(array('controller' => 'user', 'action' => 'logout')); ?>">logout</a> 
                 		: <a href="<?php echo Router::url(array('controller' => 'user', 'action' => 'profile')); ?>">profile</a>
                 		
                 		<?php else: ?>
                 		<a href="<?php echo Router::url(array('controller' => 'user', 'action' => 'login')); ?>">login</a> 
                 		: <a href="<?php echo Router::url(array('controller' => 'user', 'action' => 'register')); ?>">register</a>
                 		
                 		<?php endif; ?>
                 	</span>
                 	</div>
                 </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div><!-- CLOSE HEADER WRAPPER -->
<div class="container">
