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

$page = 'vendor';

/**
 * Initializing
 * 
 * Now that everything is loaded and ready, you can initialize
 */
do_action('init');

// START META AND TITLE INFO FOR PAGE
$meta_desc = '<META NAME="DESCRIPTION" CONTENT="' .SITE_DESC. '" />'."\n";
$meta_keywords = '<META NAME="KEYWORDS" CONTENT="' .SITE_KEYWORDS. '" />'."\n";
$title ='<title>' .SITE_TITLE.META_TITLE_LOGIN. '</title>';
// END

$cl = new contentLogic(); // CONTENT BLOCKS LOGIC ENGINE

// INITIALIZE ENGINES
$main = new main('vendor'); // MAIN PAGE ENGINE
// END

//COMPILE META INFO
$meta = $meta_desc.$meta_keywords.$title;
// END

// CALL TEMPLATE SYSTEM AND OUTPUT TO TEMPLATE
$template = ABSPATH.'inc/tpl/shell.inc';
$output = new main_output($template); //template is set in the config file.  You can overwrite here by simply putting an absolute path (example: replace $template with 'inc/mytemplate.inc').

// replace tags from template
$output->replace_tags(array(
	'header' => get_show_view('header'),
	'footer' => get_show_view('footer'),
	'shell_content' => $main->get_output()
));

// Call the output
$output->output_values();

