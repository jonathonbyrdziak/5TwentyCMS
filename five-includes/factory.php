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
 * Framework Factory class
 *
 * @static
 * @since	1.5
 */
class FiveFactory
{
	/**
	 * Get a configuration object
	 *
	 * Returns a reference to the global object, only creating it
	 * if it doesn't already exist.
	 *
	 * @access public
	 * @param string	The path to the configuration file
	 * @param string	The type of the configuration file
	 */
	function &getConfig()
	{
		static $instance;

		if (!is_object($instance))
		{
			$instance = FiveFactory::_createConfig();
		}

		return $instance;
	}

	/**
	 * Get an user object
	 *
	 * Returns a reference to the global {@link JUser} object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param 	int 	$id 	The user to load - Can be an integer or string - If string, it is converted to ID automatically.
	 *
	 * @access public
	 * @return object JUser
	 */
	function &getUser($id = null)
	{
		static $users;
		
		if (!isset($users))
		{
			$users = array();
		}
		
		if(is_null($id))
		{
			$id = get_current_user_id();
		}
		
		if (!isset($users[$id]))
		{
			$user = FiveTable::getInstance('user');
			$user->load($id);
			$users[$id] =& $user;
		}
		
		return $users[$id];
	}

	/**
	 * Get a database object
	 *
	 * Returns a reference to the global {@link FiveDatabase} object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return object FiveDatabase
	 */
	function &getDBO( $database = null )
	{
		static $instances;
		
		if (!isset($instances))
		{
			$instances = array();
		}
		
		if (is_null($database))
		{
			$database = config('base.database');
		}
		
		if (!isset($instances[$database]) || !is_object($instances[$database]))
		{
			//get the debug configuration setting
			$debug = config('base.debug');

			$instances[$database] = FiveFactory::_createDBO( $database );
			$instances[$database]->debug($debug);
		}

		return $instances[$database];
	}

	/**
	 * Return a reference to the {@link JDate} object
	 *
	 * @access public
	 * @param mixed $time The initial time for the JDate object
	 * @param int $tzOffset The timezone offset.
	 * @return object JDate
	 * @since 1.5
	 */
	function &getDate($time = 'now', $tzOffset = 0)
	{
		static $instances;
		static $classname;
		
		if(!isset($instances)) 
		{
			require_once INCLUDES.DS.'date.php';
			$instances = array();
		}

		if(!isset($classname)) 
		{
			$classname = 'FiveDate';
		}
		$key = $time . '-' . $tzOffset;

		if(!isset($instances[$classname][$key])) 
		{
			$tmp = new $classname($time, $tzOffset);
			//We need to serialize to break the reference
			$instances[$classname][$key] = serialize($tmp);
			unset($tmp);
		}

		$date = unserialize($instances[$classname][$key]);
		if($time == 'now') $date->reset();
		return $date;
	}

	/**
	 * Create a configuration object
	 *
	 * @access private
	 * @param string	The path to the configuration file
	 * @param string	The type of the configuration file
	 * @return object JRegistry
	 * @since 1.5
	 */
	function &_createConfig()
	{
		$five_config = fiveConfigurations();
		return $five_config;
	}

	/**
	 * Create an database object
	 *
	 * @access private
	 * @param string $database	The name of the database connection to use
	 * @return object FiveDatabase
	 * @since 1.5
	 */
	function &_createDBO( $database )
	{
		$host 		= config("database.$database.host");
		$user 		= config("database.$database.username");
		$password 	= config("database.$database.password");
		$name		= config("database.$database.name");
		$prefix 	= config("database.$database.prefix");
		$driver 	= config("database.$database.driver");
		$debug 		= config("base.debug");

		$options	= array ( 'driver' => $driver, 'host' => $host, 'user' => $user, 'password' => $password, 'database' => $name, 'prefix' => $prefix );

		$db =& FiveDatabase::getInstance( $options );

		if ( !is_object($db) ) {
			header('HTTP/1.1 500 Internal Server Error');
			exit('Database Error: ' . $db );
		}

		if ($db->getErrorNum() > 0) {
			trigger_error( 'FiveDatabase::getInstance: Could not connect to database <br />' . 'library:'.$db->getErrorNum().' - '.$db->getErrorMsg(), E_USER_ERROR );
		}

		$db->debug( $debug );
		return $db;
	}
}