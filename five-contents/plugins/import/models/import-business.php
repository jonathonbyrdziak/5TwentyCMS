<?php 
/**
 * @Author Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Importing
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or _die("Cannot access pages directly.");

if (!is_user_logged_in()) redirect( url('user/login') );

$import = false;
if ($file = BRequest::getVar('import', false, 'files'))
{
	$import = save_file($file, 'csv', 'import');
	
	
	
	
}

require $view;