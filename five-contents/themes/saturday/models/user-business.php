<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Five Twenty CMS
 * @SubPackage PublicMarketSpace
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");

show_view('header-w-wrap');
show_view('sidebar-profile-left');

global $cl, $page;
require_once ABSPATH.'inc'.DS.'func_business.php';

// declaring globals
$page = 'profile';

$cl = new contentLogic();
$main = new main($page);
$content = $main->get_output();

require $view;

show_view('footer-w-wrap');


