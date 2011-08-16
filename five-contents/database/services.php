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
class FiveTableServices extends FiveTable
{
	/**
	 * Unique id
	 *
	 * @var int
	 */
	var $id				= null;
	
	/**
	 * 
	 *
	 * @var int
	 */
	var $bizid			= null;
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $promo			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $bio			= null;
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $slogan			= null;
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $dateAdded		= null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__services', 'id', $db );

		//initialise
		$this->id		= 0;
		$this->bizid	= 0;
	}

	/**
	 * Saving this
	 *
	 * @return boolean True is satisfactory
	 */
	function store( $updateNulls=false )
	{
		/*
		if ($img = BRequest::getVar('img', false, 'files')) {
			//delete the old
			if (!is_null($this->img)) @unlink($this->img);
			//save the new
			$this->img = save_file($img, 'image', 'product');
		}
		*/
		return parent::store($updateNulls);
	}

	/**
	 * Validation and filtering
	 *
	 * @return boolean True is satisfactory
	 */
	function check()
	{
		// Validate business information
		if (trim( $this->promo ) == '') {
			$this->setError( 'Please enter the promo text.' );
			return false;
		}
		
		if ($this->dateAdded == null) {
			// Set the registration timestamp
			$now =& FiveFactory::getDate();
			$this->dateAdded = strtotime($now->toMySQL());
		}

		return true;
	}

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadByBizID( $oid=null )
	{
		$k = 'bizid';

		if ($oid !== null) {
			$this->$k = $oid;
		}

		$oid = $this->$k;

		if ($oid === null) {
			return false;
		}
		$this->reset();

		$db =& $this->getDBO();

		$query = 'SELECT *'
		. ' FROM '.$this->_tbl
		. ' WHERE '.$k.' = '.$db->Quote($oid);
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
	
	
/**                    **********************
 * 
 * The following functions are designed to grab multiple 
 * product records from the database and return an array
 * of product models.
 * 
 * 
 */
	
	/**
	 * Method is responsible for loading the proper sql system
	 * 
	 * @param unknown_type $colName
	 * @param unknown_type $asstName
	 * @return Ambigous <unknown, string, unknown>|Ambigous <string, unknown>|unknown
	 */
	function getServices( $colName=null, $asstName=null )
	{
		if (!$colName && !$asstName)
		{
			return $this->getAllServices();
		}
		
		elseif ($catName && $colName && !$asstName)
		{
			//return $this->getCollectionServices( $catName, $colName );
		}
		
		elseif ($catName && $colName && $asstName)
		{
			//return $this->getAssortmentServices( $catName, $colName, $asstName );
		}
	}
	
	/**
	 * Function is responsible for listing the ALL of the products from
	 * the database without querying for any categories.
	 * 
	 * @return unknown|string
	 */
	function getAllServices()
	{
		$db =& $this->getDBO();
		$db->resetTree();
		
		$db->setTree('SELECT', '`'.$this->_tbl.'`.*');
		$db->setTree('FROM', '`#__rel_serv_cca`');
		$db->setTree('INNER JOIN', '`'.$this->_tbl.'` ON `#__rel_serv_cca`.bizid='.$this->_tbl.'.bizid');
		$db->setTree('WHERE', '`#__rel_serv_cca`.catid = '.$db->Quote('108'));
		
		
		if (BRequest::getCmd('p',false) == 'h' && BRequest::getCmd('s',false) == 'r' && BRequest::getCmd('r',false) == 'o')
		{
			$db->setTree('ORDER BY', '`'.$this->_tbl.'`.`price` DESC');
		}
		elseif (BRequest::getCmd('p',false) == 'l' && BRequest::getCmd('s',false) == 'r' && BRequest::getCmd('r',false) == 'o')
		{
			$db->setTree('ORDER BY', "`{$this->_tbl}`.`price` ASC");
		}
		elseif (BRequest::getCmd('s',false) == 'l' && BRequest::getCmd('r',false) == 'o')
		{
			//@todo doesn't work as expected
			$db->setTree('SELECT', "sum((`likes`.`like`)) AS `tlikes`");
			$db->setTree('LEFT JOIN', "`likes` ON `{$this->_tbl}`.`id` = `likes`.`itemid`");
			$db->setTree('ORDER BY', "tlikes ASC");
		}
		elseif (BRequest::getCmd('s',false) == 'r' && BRequest::getCmd('r',false) == 'n')
		{
			$db->setTree('ORDER BY', "`{$this->_tbl}`.`dateAdded` DESC");
		}
		elseif (BRequest::getCmd('s',false) == 'r' && BRequest::getCmd('r',false) == 'o')
		{
			$db->setTree('ORDER BY', "`{$this->_tbl}`.`dateAdded` ASC");
		}
		elseif (BRequest::getCmd('s',false) == 'r')
		{
			//@todo doesn't work as expected
			$db->setTree('SELECT', "sum((`ratings`.`like`)) AS `ratingsum`");
			$db->setTree('LEFT JOIN', "`ratings` ON `{$this->_tbl}`.`id` = `ratings`.`itemid`");
			$db->setTree('ORDER BY', "ratingsum ASC");
		}
		
		//default ordering
		if (empty($sql['ORDER BY']))
			$db->setTree('ORDER BY', "`$this->_tbl`.dateAdded ASC");
		
		$per_page = config('products.per_page');
		$db->setTree('LIMIT', $per_page);
		
		if ($offset = (BRequest::getInt('ppage',1)-1) * $per_page) {
			$db->setTree('OFFSET', $offset);
		}
		
		$q = $db->queryTree();
		//echo _520($q);
		if ($results = $db->loadList( $this->_tbl_key, $this->_type )) {
			return $results;
		}
		return false;
	}

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function getLike( $query=null, $k='promo' )
	{
		if ($query === null) {
			return array();
		}
		
		$db =& $this->getDBO();

		$query = 'SELECT *'
		. ' FROM '.$this->_tbl
		. " WHERE $k LIKE '%$query%'";
		$db->setQuery( $query );

		if ($results = $db->loadList( $this->_tbl_key, $this->_type )) {
			return $results;
		}
		else
		{
			$this->setError( $db->getErrorMsg() );
			return false;
		}
	}
}