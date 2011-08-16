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

// AJAX CALL - SET LIKE
if(BRequest::getVar('call', false) == 'sl') 
{
	if(
		( preg_match('/^[0-9]{1,20}$/i',BRequest::getVar('i', false)) ) &&
		( preg_match('/^[a-z]{4,20}$/i',BRequest::getVar('method', false)) )
	) {
		$return = pms_set_like();
	}
	else
	{
		$return = 0;
	}
	print($return);
	exit();
							
}
// AJAX CALL - SET Rating
elseif(BRequest::getVar('call', false) == 'sr') 
{
	if(
		( preg_match('/^[0-9]{1,20}$/i',BRequest::getVar('i', false)) ) &&
		( preg_match('/^[a-z]{4,20}$/i',BRequest::getVar('method', false)) )
	) 
	{
		$return = pms_set_rating();
	}
	else
	{
		$return = 0;
	}
	print($return);
	exit();
							
}




require_once ABSPATH.'inc'.DS.'func_index.php';
require_once ABSPATH.'bin'.DS.'XPM4'.DS.'MAIL.php';

global $cl;
$keywords = get_keywords();

// START META AND TITLE INFO FOR PAGE
$meta_desc = '<meta name="description" content="' .SITE_DESC. ' - ';
$meta_keywords = '<meta name="keywords" content="' .SITE_KEYWORDS. ' - ';
$title ='<title>';

// CONTENT ENGINE
$cl = new contentLogic();

// INITIALIZE THE MAIN ENGINE
$main = new main();

// END META AND TITLE INFO FOR PAGE
$meta_desc .= '" />'."\n";
$meta_keywords .= '" />'."\n";
$meta_keywords = '<meta name="keywords" content="'. $keywords .'" />';
$title .= SITE_TITLE.'</title>';
$meta = $meta_desc.$meta_keywords.$title;

$content = $main->get_content();
require $view;