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

//adding actions
add_action('request', 'five_request');
add_filter('code-shell_content', 'five_display_page');
add_filter('code-content', 'five_main_content');

/**
 * Function is responsible for setting the current page. this function will
 * use the information provided in the requested string.
 *
 */
function five_request()
{
	//initializing
	global $page;
	$page = 'main';
	$get = BRequest::get('get');
	
	if ($tmp = BRequest::getVar('page'))
	{
		$page = $tmp;
	}
}

/**
 * We're actually calling the page here
 *
 * @return unknown
 */
function five_display_page()
{
	//initializing
	global $page;
	switch ($page)
	{
		default:
			return get_show_view('page-404');
			break;
		case 'main':
			return get_show_view('page-main');
			break;
		case 'intro':
			return get_show_view('page-intro');
			break;
		case 'login':
			require_once dirname(__file__).DS."profile.php";
			return get_show_view('page-login');
			break;
		case 'register':
			require_once dirname(__file__).DS."profile.php";
			return get_show_view('page-register');
			break;
		case 'forgot':
			require_once dirname(__file__).DS."profile.php";
			return get_show_view('page-forgot');
			break;
	}
}

/**
 * Function is responsible for calling in the main page content.
 *
 * @return unknown
 */
function five_main_content()
{
	require_once dirname(__file__).DS."main.php";
	return get_show_view('content-homepage');
}

