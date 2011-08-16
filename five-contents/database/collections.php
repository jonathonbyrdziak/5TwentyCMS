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
class FiveTableCollections extends FiveTable
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
	var $name			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $url			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $sortOrder		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $status			= null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__collections', 'id', $db );

		//initialise
		$this->id			= 0;
		$this->_relTable 	= 'rel_cats_cols';
	}
	
	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadAll( $parentCat=null )
	{
		$parent = FiveTable::getInstance('categories');
		$parent->loadByName($parentCat);
		
		$this->reset();
		
		$db =& $this->getDBO();
		
		if (!$parent->id)
		{
			$query = 'SELECT *'
			. ' FROM '.$this->_tbl;
		}
		else
		{
			$query = 'SELECT *'
			. ' FROM '.$this->_relTable
			. ' LEFT JOIN '.$this->_tbl.' col ON '.$this->_relTable.'.`colid` = col.`id`'
			. ' WHERE `catid` = '.$db->Quote($parent->id)
			. ' ORDER BY col.`sortOrder` ASC, col.`name` ASC'
			;
		}
		$db->setQuery( $query );
		
		if ($result = $db->loadList( $this->_tbl_key, $this->_type )) {
			return $result;
		}
		else
		{
			$this->setError( $db->getErrorMsg() );
			return false;
		}
	}

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadByParentAndName( $parentCat, $name=null )
	{
		$parent = FiveTable::getInstance('categories');
		$parent->loadByName($parentCat);
		
		if ($name === null) {
			return false;
		}
		$this->reset();
		
		$db =& $this->getDBO();
		
		$query = 'SELECT `col`.*'
		. ' FROM '.$parent->_tbl.' as cat'
		. ' LEFT JOIN '.$this->_relTable.' ON '.$this->_relTable.'.`catid` = `cat`.`id`'
		. ' LEFT JOIN '.$this->_tbl.' col ON '.$this->_relTable.'.`colid` = col.`id`'
		. ' WHERE `col`.`name` = '.$db->Quote($name);
		;
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