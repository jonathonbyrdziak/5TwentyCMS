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

//actions
add_action('notifications', 'show_errors');
add_action('notifications', 'show_notifications');
add_action('head', 'show_stylesheets');
add_action('head', 'show_scripts');

/**
 * Function is responsible for redirecting from insecure access
 *
 * @param string $message
 * @param string $url
 */
function _die( $message = "Cannot access pages directly.", $url = null )
{
	//making sure that we have some url set
	if (is_null($url)) $url = url();
	
	//redirecting
	header("Location: $url");
	exit();
}

/**
 * Function is responsible for returning the current classes for 
 * this given page
 */
function body_classes()
{
	$route = Router::parse();
	
	$class = $route['controller'];
	if (!empty($route['plugin'])) $class .= ' '.$route['plugin'];
	if (!empty($route['action'])) $class .= ' '.$route['action'];
	
	echo $class;
}

/**
 * Function is responsible for remembering all of the given
 * errors
 * 
 * @param unknown_type $error
 * @return unknown
 */
function set_error( $error = null )
{
	static $e;
	
	if (!isset($e))
	{
		$e = array();
	}
	
	if (!is_null($error) && is_array($error))
	{
		foreach ($error as $err)
			$e[] = $err;
	}
	elseif (!is_null($error))
	{
		$e[] = $error;
	}
	
	if (is_null($error))
	{
		$errors = $e;
		$e = array();
		return $errors;
	}
	return true;
}

/**
 * Displays the errors in a user friendly box
 */
function show_errors()
{
	$errors = set_error();
	
	if (!empty($errors))
	{
		$errors = implode('<br/>', $errors);
		show_view('five-notification-error', array('notification' => $errors));
	}
}

/**
 * Function is responsible for remembering all of the given
 * errors
 * 
 * @param unknown_type $error
 * @return unknown
 */
function set_notice( $error = null )
{
	static $e;
	
	if (!isset($e))
	{
		$e = array();
	}
	
	if (!is_null($error) && is_array($error))
	{
		foreach ($error as $err)
			$e[] = $err;
	}
	elseif (!is_null($error))
	{
		$e[] = $error;
	}
	
	if (is_null($error))
	{
		$errors = $e;
		$e = array();
		return $errors;
	}
	return true;
}

/**
 * Displays the errors in a user friendly box
 */
function show_notifications()
{
	$errors = set_notice();
	
	if (!empty($errors))
	{
		$errors = implode('<br/>', $errors);
		show_view('five-notification-success', array('notification' => $errors));
	}
}

/**
 * 
 * @param unknown_type $path
 */
function register_script( $path = null, $type = 'text/javascript' )
{
	static $pathes;
	
	if (!isset($pathes))
	{
		$pathes = array();
	}
	
	if (!is_null($path))
	{
		$pathes[$path] = $type;
	}
	
	return $pathes;
}

/**
 * Function is responsible for printing out all of the stylesheets
 */
function show_scripts()
{
	//initializing
	$styles = register_script();
	
	foreach((array)$styles as $path => $type)
	{
		echo '<script type="'.$type.'" src="'.url($path).'"></script>';
	}
}

/**
 * 
 * @param unknown_type $path
 */
function register_stylesheet( $path = null, $type = 'text/css' )
{
	static $pathes;
	
	if (!isset($pathes))
	{
		$pathes = array();
	}
	
	if (!is_null($path))
	{
		$pathes[$path] = $type;
	}
	
	return $pathes;
}

/**
 * Function is responsible for printing out all of the stylesheets
 */
function show_stylesheets()
{
	//initializing
	$styles = register_stylesheet();
	
	foreach((array)$styles as $path => $type)
	{
		echo '<link rel="stylesheet" type="'.$type.'" href="'.url($path).'"/>';
	}
}

