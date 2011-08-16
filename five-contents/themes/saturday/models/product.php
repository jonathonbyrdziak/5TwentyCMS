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

global $cl;
require_once ABSPATH.'inc'.DS.'func_product_root.php';


// START META AND TITLE INFO FOR PAGE
$meta_desc = '<meta name="description" content="' .SITE_DESC. ' - '; 
$meta_keywords = '<meta name="keywords" content="' .SITE_KEYWORDS. ' - ';  
$title = '<title>';

$cl = new contentLogic();

$main = new main();

// SETUP TEMPLATE
$template = ABSPATH.'inc/tpl/shell.inc';
$output = new main_output($template);

// CONTINUE META \\
$title .= ' - ' .SITE_TITLE. '</title>';
$meta_keywords .= '" />'."\n";
$meta_desc .= '" />'."\n";
$meta = $meta_desc.$meta_keywords.$title;

// STRIP THE TAGS
$output->replace_tags(array(	
	'header' => get_show_view('header'),
	'footer' => get_show_view('footer'),
	'shell_content' => $main->get_output()
));

// OUTPUT TO SCREEN
$output->output_values();

