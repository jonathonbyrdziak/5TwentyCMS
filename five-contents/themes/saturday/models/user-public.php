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

$route = Router::parse();
if (!isset($route['pass'][0]))
{
	if (!is_user_logged_in()) redirect(url());
	$route['pass'][0] = get_publicid(get_current_user_id());
}
BRequest::setVar('publicid',$route['pass'][0]);

global $cl, $page;
$page = 'profile';
require_once ABSPATH.'inc'.DS.'func_public.php';

// START META AND TITLE INFO FOR PAGE
$meta_desc = '<META NAME="DESCRIPTION" CONTENT="' .SITE_DESC. '" />'."\n";
$meta_keywords = '<META NAME="KEYWORDS" CONTENT="' .SITE_KEYWORDS. '" />'."\n";
$title ='<title>' .SITE_TITLE. '</title>';
// END

// CONTENT ENGINE
$cl = new contentLogic();
// END

// INITIALIZE THE MAIN ENGINE
$main = new main();
// END

//COMPILE META TAG
$meta = $meta_desc.$meta_keywords.$title;

// CALL TEMPLATE SYSTEM AND OUTPUT TO TEMPLATE
$template = ABSPATH.'inc/tpl/shell.inc';
$output = new main_output($template); 

// replace tags from template
$output->replace_tags( array(
	'header' => get_show_view('header'),
	'footer' => get_show_view('footer'),
	'shell_content' => $main->get_output()
));

// Call the output
$output->output_values();