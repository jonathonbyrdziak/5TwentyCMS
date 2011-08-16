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

global $cl, $page;
require_once ABSPATH.'inc'.DS.'func_profile.php';

/**
 * declaring globals
 */
$page = 'forgot';

// START META AND TITLE INFO FOR PAGE
$meta_desc = '<meta name="description" content="' .SITE_DESC. ' - ';
$meta_keywords = '<meta name="keywords" content="' .SITE_KEYWORDS. ' - ';
$meta_addtl = '';
$title ='<title>' .SITE_TITLE.META_TITLE_FORGOT;
// END

// INITIALIZE THE MAIN ENGINE
$main = new main('forgot');

// CONTENT ENGINE
$cl = new contentLogic();

//COMPILE META TAG
$meta_desc .= '" />'."\n";
$meta_keywords .= '" />'."\n";
$title .= '</title>';
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
