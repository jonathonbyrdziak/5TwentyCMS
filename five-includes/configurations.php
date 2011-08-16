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
 * Function is responsible for returning the configurations file.
 *
 * @return unknown
 */
function fiveConfigurations()
{
	//initializing
	static $config;
	
	if (!isset($config)) 
	{
		$config = simplexml_load_file( CONTENTS."config.xml" );
	}
	
	return $config;
}

/**
 * Function is responsible for reading and writting configurations
 * @param unknown_type $property
 * @param unknown_type $value
 */
function config( $property, $value = null )
{
	$conf =& FiveFactory::getConfig();
	$tmp = $conf;
	$parts = explode('.', $property);
	
	if (!is_null($value))
	{
		//@TODO Save the configuration
	}
	
	foreach ((array)$parts as $part)
	{
		if (!$part) return false;
		if (!isset($tmp->$part)) return false;
		
		$tmp = $tmp->$part;
	}
	
	if (count(get_object_vars($tmp)) < 2)
		return (string)$tmp;
	return $tmp;
}
