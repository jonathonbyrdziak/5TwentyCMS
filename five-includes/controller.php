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
add_action('init', 'five_do_ajax', 1000);
add_action('init', 'redirect_if_forced', 1);
add_action('system', 'system_pathes');

/**
 * Session stuff, didn't know where I should put it.
 */
function set_post_sessions() {
	//save any post values prepended with "setting_"
	foreach( $_POST as $k => $v )
		if( substr( $k, 0, 8 ) == 'setting_' )
			$_SESSION[$k] = $v;
}

function default_session() {
	$this->save( 'some_setting', 'a_value' );
}

//identifier, value
function session_save($i,$v) {
	$_SESSION["setting_{$i}"] = $v;
}

//identifier, default
function session_get($i,$d=false) {
	if( isset( $_SESSION["setting_{$i}"] ) )
		return $_SESSION["setting_{$i}"];
	else
		return $d;
}
	
/**
 * Controller.
 * 
 * This function will locate the associated element and display it in the
 * place of this function call
 * 
 * @param string $name
 */
function get_show_view( $name = null )
{
	//initializing variables
	$html = "";
	$args = func_get_args();
	$folders = add_view_path();
	
	$view = files_find($folders, $name.".php");
	$model = files_find($folders, "models".DS.$name.".php");
	
	if (is_null($name)) return false;
	if (!$view && !$model) return false;
	
	$path = $view;
	
	if (file_exists($model))
	{
		ob_start();
		require $model;
		$html = ob_get_clean();
	}
	else
	{
		ob_start();
		require $path;
		$html = ob_get_clean();
	}
	
	if ($html)
		$html = $html; //replace_template_codes( $html, @$args[1] ); ****this was throwing an error but looks important
	return $html;
}

/**
 * Function prints out the get_show_view()
 * 
 * @see get_show_view
 */
function show_view()
{
	$args = func_get_args();
	echo call_user_func_array('get_show_view', $args);
}

/**
 * Function is responsible for running any ajax calls that
 * come through.
 * 
 */
function five_do_ajax()
{
	//reasons to return
	if (!$view = BRequest::getVar('five_ajax', false)) return false;
	do_action("five_ajax_$view");
	show_view($view);
	die();
}

/**
 * Function prints out the twc_get_show_view()
 * 
 * @param string $name
* @see twc_get_show_view
 */
function add_view_path( $path = null )
{
	static $controller_paths;
	
	if (!isset($controller_paths))
	{
		$controller_paths = array();
	}
	
	if (!is_null($path))
	{
		$controller_paths[$path] = $path;
	}
	
	return $controller_paths;
}

/**
 * Function is responsible for defining the default system pathes
 */
function system_pathes()
{
	add_view_path( get_theme_dir() );
	add_view_path( INCLUDES."views" );
}

/**
 * Searches the directory paths for a given file.
 *
 * @access protected
 * @param array|string $path An path or array of path to search in
 * @param string $file The file name to look for.
 * @return mixed The full path and file name for the target file, or boolean false if the file is not found in any of the paths.
 * @since 1.5
 */
function files_find($paths, $file)
{
	settype($paths, 'array'); //force to array
	
	// start looping through the path set
	foreach ($paths as $path)
	{
		// get the path to the file
		$fullname = FivePath::clean($path.DS.$file);
		
		// is the path based on a stream?
		if (strpos($path, '://') === false)
		{
			// not a stream, so do a realpath() to avoid directory
			// traversal attempts on the local file system.
			$path = realpath($path); // needed for substr() later
			$fullname = realpath($fullname);
		}
		
		// the substr() check added to make sure that the realpath()
		// results in a directory registered so that
		// non-registered directores are not accessible via directory
		// traversal attempts.
		
		if (file_exists($fullname) && substr($fullname, 0, strlen($path)) == $path) 
		{
			return $fullname;
		}
	}
	
	// could not find the file in the set of paths
	return false;
}

/**
 * Function prints out the job_get_show_view()
 * 
 * @param string $name
 * @see get_show_view
 */
function set_controller_path( $name = null )
{
	static $controller_paths;
	
	if (!isset($controller_paths))
	{
		$controller_paths = array();
	}
	
	if (!is_null($name))
	{
		$controller_paths[$name] = $name;
	}
	
	return $controller_paths;
}

/**
 * function is responsible for returning the themes path
 */
function get_theme_dir()
{
	$conf = FiveFactory::getConfig();
	return THEMES.$conf->theme->name.DS;
}

/**
 * function is responsible for returning the themes path
 */
function get_theme_path()
{
	$conf = FiveFactory::getConfig();
	return url().'five-contents/themes/'.$conf->theme->name.'/';
}

/**
 * Function is responsible for returning the base url
 */
function home_url()
{
	return url();
}

/**
 * Function is responsible for returning a well formed url
 *
 * @param unknown_type $request
 */
function url( $request = '' )
{
	//clean url
	$request = str_replace(array(config('base.url'), config('base.secure_url')), '', $request);
	
	//set the proper protocal
	if (!is_force_ssl())
	{
		$request = config('base.url').$request;
	}
	else
	{
		$request = config('base.secure_url').$request;
	}
	
	return $request;
}

/**
 * 
 * @param unknown_type $file
 * @param unknown_type $path
 */
function plugin_url( $file, $path )
{
	$url = str_replace(array(ABSPATH), '', dirname($file));
	return url($url.'/'.$path);
}

/**
 * Function is responsible for redirecting the user if the page should
 * be forced through ssl.
 *
 * @return unknown
 */
function redirect_if_forced()
{
	if (!is_force_ssl()) return false;
	force_ssl();
}

/**
 * Function is responsible for checking to see if the given 
 * page needs to be ssl
 *
 */
function is_force_ssl()
{
	//loading configuration settings
	global $page;
	$five_config = fiveConfigurations();
	$pages = (array)explode(',', $five_config->base->force_ssl);
	
	//reasons to return
	if ($five_config->base->force_ssl == '') return false;
	if (!in_array($page, $pages)) return false;
	
	return true;
}

/**
 * Function is responsible for forcing the page to be ssl
 *
 * @return unknown
 */
function force_ssl( $url = null )
{
	//loading configuration settings
	$five_config = fiveConfigurations();
	
	//reasons to return
	if ($five_config->base->force_ssl == '') return false;
	if (BRequest::getVar("HTTPS", false) == "on") return false;
	if (BRequest::getVar("SERVER_PORT", false) == 443) return false;
	
	if (is_null($url))
	{
		//redirect( SITE_BASEURL_SECURE.substr($_SERVER['REQUEST_URI'],1) );
		$url = BRequest::getVar("SERVER_NAME",url()).BRequest::getVar("REQUEST_URI");
	}
	
	//redirect to secure url
	redirect( $url );
}

/**
 * Determine if SSL is used.
 *
 * @since 2.6.0
 *
 * @return bool True if SSL, false if not used.
 */
function is_ssl() {
	if ( isset($_SERVER['HTTPS']) ) {
		if ( 'on' == strtolower($_SERVER['HTTPS']) )
			return true;
		if ( '1' == $_SERVER['HTTPS'] )
			return true;
	} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		return true;
	}
	return false;
}
				
/**
 * Check if this is Jon
 * 
 */
function is_520()
{
	if ($_SERVER['REMOTE_ADDR'] == '24.19.145.232'||$_SERVER['REMOTE_ADDR'] == '67.233.234.10') return true;
	return false;
}
	
/**
 * Quick dump of an variables that are sent as parameters to this function.
 * Make sure to enter your IP address so that it doens't display for anybody
 * but yourself.
 * 
 * @return null
 */
function _520()
{
	if (!is_520()) return;
	
	//initializing variables
	$variables = func_get_args();
	static $debug;

	//reasons to return
	if (empty($variables))
	{
		echo $debug;
		die();
	}

	foreach ($variables as $variable)
	{
		$string = "";
		if (!is_string($variable))
		{
			ob_start();
			echo  '<pre>';
			print_r($variable);
			echo  '</pre>';
			$string = ob_get_clean();
		}
		elseif (is_bool($variable))
		{
			ob_start();
			var_dump($variable);
			$string = ob_get_clean();
		}
		else
		{
			$string = $variable;
		}

		if (!isset($debug))
		{
			$debug = $string;
		}
		else
		{
			$debug .= '<BR>'.$string;
		}
	}

	return $string;
}