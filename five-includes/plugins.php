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

//system hooks
add_action('system', 'five_load_plugins');

/**
 * Returns array of plugin files to be included in global scope.
 * 
 * @access private
 * @since 3.0.0
 * @return array Files to include
 */
function five_get_plugins() {
	$plugins = array();
	if ( !is_dir( PLUGINS ) )
		return $plugins;
	if ( ! $dh = opendir( PLUGINS ) )
		return $plugins;
	while ( ( $plugin = readdir( $dh ) ) !== false )
	{
		if ( is_dir( PLUGINS.$plugin ) && ($plugin != "." && $plugin != "..") )
			$plugins[] = PLUGINS.$plugin.DS.'index.php' ;
		if ( substr( $plugin, -4 ) == '.php' )
			$plugins[] = PLUGINS.$plugin;
	}
	closedir( $dh );
	sort( $plugins );

	return $plugins;
}

/**
 * Function is responsible for loading all of the functions
 *
 */
function five_load_plugins()
{
	//initializing
	$plugins = five_get_plugins();
	
	foreach ((array)$plugins as $plugin)
	{
		require_once $plugin;
	}
}