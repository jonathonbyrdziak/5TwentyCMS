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
class FiveTableAssortments extends FiveTable
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
		parent::__construct( '#__assortments', 'id', $db );

		//initialise
		$this->id			= 0;
		$this->_relTable 	= 'rel_cac_assts';
	}

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadByParentParentAndName( $parentCat, $parentCol, $name=null )
	{
		$category = FiveTable::getInstance('categories');
		$category->loadByName(urldecode($parentCat));
		
		$collection = FiveTable::getInstance('collections');
		$collection->loadByParentAndName(urldecode($parentCat), urldecode($parentCol));
		
		$cacid = get_cacid($category->id,$collection->id);
		
		$this->reset();
		
		$db =& $this->getDBO();
		
		$query = "select `".$this->_tbl."`.* from rel_cac_assts rca 
					join rel_cats_cols rcac on rca.cacid=rcac.id 
					left join categories cat on rcac.catid=cat.id
					left join collections col on rcac.colid=col.id
					left join assortments on rca.asstid = `".$this->_tbl."`.id
						where rca.cacid='" .$cacid. "' AND `".$this->_tbl."`.name = '".urldecode($name)."'
						order by `".$this->_tbl."`.sortOrder asc, `".$this->_tbl."`.name asc";

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

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadByParents( $parentCat=null, $parentCol=null )
	{
		$category = FiveTable::getInstance('categories');
		$category->loadByName($parentCat);
		
		$collection = FiveTable::getInstance('collections');
		$collection->loadByParentAndName($parentCat, $parentCol);
		$cacid = get_cacid($category->id,$collection->id);
		
		$this->reset();
		
		$db =& $this->getDBO();
		
		$query = "select `".$this->_tbl."`.* from rel_cac_assts rca 
					join rel_cats_cols rcac on rca.cacid=rcac.id 
					left join categories cat on rcac.catid=cat.id
					left join collections col on rcac.colid=col.id

					left join assortments on rca.asstid = `".$this->_tbl."`.id
						where rca.cacid='" .$cacid. "'
						order by `".$this->_tbl."`.sortOrder asc, `".$this->_tbl."`.name asc";
		
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
}