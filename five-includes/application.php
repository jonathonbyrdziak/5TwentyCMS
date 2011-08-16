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

/**
 * Actions
 */
add_action('application', 'do_template');

/**
 * Function is responsible for locating the template to use
 * and showing it to the user
 */
function do_template()
{
	//called last as default
	Router::connect('/:controller/:action/*');
	
	$template = '';
	$route = Router::parse();
	
	if (is('five-contents')		&& $template = get_real_template()) {}
	elseif (have_controller()	&& $template = get_general_template()) {}
	
	// if we can't figure anything out, then return the 404
	if (!$template = apply_filters( 'template_include', $template )) {
		$template = get_home_template();
	}
	
	if( !headers_sent() && config('theme.gzip') )
	{
		ob_start("ob_gzhandler");
	}
	
	//_520($template);_520();
	
	// we want to get the template first and then echo it. 
	// this allows redirects from any point within our program
	// this allows us to gzip the output as well
	echo get_show_view( $template );
}

/**
 * Function is responsible for returning the business template if that's what we're 
 * @param unknown_type $template
 * @return unknown
 */
function is( $param )
{
	$route = Router::parse();
	//_520($route);_520();
	
	if (!isset($route['controller'])) return false;
	if ($route['controller'] == $param) return true;
	return false;
}

/**
 * Function is responsible for determining if this is the homepage.
 */
function have_controller()
{
	$route = Router::parse();
	
	if (!isset($route['controller'])) return false;
	return true;
}

/**
 * Function is responsible for determining if this is the homepage.
 */
function have_action()
{
	$route = Router::parse();
	
	if (!isset($route['action'])) return false;
	return true;
}

/**
 * Function is responsible for determining if this is the homepage.
 */
function have_plugin()
{
	$route = Router::parse();
	
	if (!isset($route['plugin'])) return false;
	return true;
}

/**
 * Retrieve path of 404 template in current or parent template.
 *
 * @since 1.0.0
 * @return string
 */
function get_404_template() {
	return get_query_template('404');
}

/**
 * Retrieve path of author template in current or parent template.
 *
 * @since 1.0.0
 * @return string
 */
function get_real_template()
{
	//initializing
	$path = $_SERVER['REQUEST_URI'];
	
	if (file_exists(ABSPATH.$path))
	{
		//if there is a file
		header('HTTP/1.1 200 OK');
		
		$parts = check_filetype($path);
		if ($parts['ext'] == 'css')
			header('Content-type: text/css');
		
		ob_start();
		require ABSPATH.$path;
		$contents = ob_get_clean();
		
		die(compress($contents));
	}
	
	return get_query_template( 'errors', array('errors-404', 'errors') );
}

/**
 * Retrieve path of author template in current or parent template.
 *
 * @since 1.0.0
 * @return string
 */
function get_general_template()
{
	//initializing
	extract($route = Router::parse());
	$templates = array();
	
	if (isset($route['pass']) && isset($route['pass'][0])) 
		$templates[] = "$controller-$action-{$route['pass'][0]}";
	
	if (have_action()) 
		$templates[] = "$controller-$action";
	
	if (have_controller()) 
		$templates[] = $controller;
	
	return get_query_template( 'index', $templates );
}

/**
 * Retrieve path of author template in current or parent template.
 *
 * @since 1.0.0
 * @return string
 */
function get_home_template()
{
	$templates = array();
	$templates[] = "home";
	$templates[] = 'index';
	
	return get_query_template( 'index', $templates );
}

/**
 * Retrieve path to a template
 *
 * Used to quickly retrieve the path of a template without including the file
 * extension. It will also check the parent theme, if the file exists, with
 * the use of {@link locate_template()}. Allows for more generic template location
 * without the use of the other get_*_template() functions.
 *
 * @since 1.0.0
 * @param string $type Filename without extension.
 * @param array $templates An optional list of template candidates
 * @return string Full path to file.
 */
function get_query_template( $type, $templates = array() )
{
	$type = preg_replace( '|[^a-z0-9-]+|', '', $type );

	if ( empty( $templates ) )
		$templates = array($type);
	
	return apply_filters( "{$type}_template", locate_template( $templates ) );
}

/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file.
 *
 * @since 1.0.0
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true. Has no effect if $load is false.
 * @return string The template filename if one is located.
 */
function locate_template( $template_names )
{
	//initializing
	$folders = add_view_path();
	$located = '';
	
	foreach ( (array) $template_names as $template_name )
	{
		$view = files_find($folders, $template_name.".php");
		$model = files_find($folders, "models".DS.$template_name.".php");
		
		if (!$view && !$model) continue;
			$located = $template_name;
			break;
	}
	
	return $located;
}