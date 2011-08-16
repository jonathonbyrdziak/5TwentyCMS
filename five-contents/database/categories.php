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
 * Users table
 *
 * @subpackage		Table
 * @since	1.0
 */
class FiveTableCategories extends FiveTable
{
	/**
	 * Unique id
	 *
	 * @var int
	 */
	var $id					= null;

	/**
	 * Unique id
	 *
	 * @var int
	 */
	var $name				= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $desc				= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $imgSideNav			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $imgSideNavInactive	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $status				= null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__categories', 'id', $db );

		//initialise
		$this->id		= 0;
	}

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadByName( $name=null )
	{
		if ($name === null) {
			return false;
		}
		$this->reset();
		
		$db =& $this->getDBO();
		
		$query = 'SELECT *'
		. ' FROM '.$this->_tbl
		. ' WHERE `name` = '.$db->Quote($name);
		$db->setQuery( $query );
		
		if ($result = $db->loadAssoc( )) {
			return $this->bind($result);
		}
		else
		{
			$this->setError( $db->getErrorMsg() );
			return false;
		}
	}
}