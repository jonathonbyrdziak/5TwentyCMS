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
class FiveTableGroups extends FiveTable
{
	/**
	 * Unique id
	 *
	 * @var int
	 */
	var $id				= null;

	/**
	 * Unique id
	 *
	 * @var int
	 */
	var $userid			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $admin			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $business		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $user			= null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__user_groups', 'id', $db );

		//initialise
		$this->id		= 0;
		$this->userid	= 0;
		$this->admin	= 0;
		$this->business	= 0;
		$this->user		= 1;
	}

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function load( $oid = null )
	{
		if ($oid !== null) {
			$this->userid = $oid;
		}

		$oid = $this->userid;

		if ($oid === null) {
			return false;
		}
		$this->reset();

		$db =& $this->getDBO();

		$query = 'SELECT *'
		. ' FROM '.$this->_tbl
		. ' WHERE `userid` = '.$db->Quote($oid);
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