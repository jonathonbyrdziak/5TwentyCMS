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
class FiveTableProducts extends FiveTable
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
	 * @var int
	 */
	var $import_id		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $secToken		= null;
	
	/**
	 * 
	 *
	 * @var int
	 */
	var $sku			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $prod_name		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $model_number	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $qty			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $sizes			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $color			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $price			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $ship_amt		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $dimensions		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $weight			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $freight		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $url_prohib		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $description	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $featured		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $feat_start		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $feat_end		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_sizes		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_colors	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $showcase		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $dateAdded		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_bo		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_proh		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $status			= null;

	/**
	 * Banner Image
	 *
	 * @var string
	 */
	var $img			= null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__products', 'id', $db );

		//initialise
		$this->id		= 0;
		$this->userid	= 0;
		$this->status	= 'submit';
	}
	
	/**
	 * Method is responsible for returning the url for this business
	 * 
	 */
	function getUrl()
	{
		return url('product/'.$this->id.'/'.slug( $this->prod_name ));
	}
	
	/**
	 * Method is responsible for returning the url for this business
	 * 
	 */
	function getCartUrl()
	{
		return url('cart.php?action=add&i='.$product->id);
	}

	/**
	 * Saving this
	 *
	 * @return boolean True is satisfactory
	 */
	function store( $updateNulls=false )
	{
		if ($img = BRequest::getVar('img', false, 'files')) {
			//delete the old
			if (!is_null($this->img)) @unlink($this->img);
			//save the new
			$this->img = save_file($img, 'image', 'product');
		}
		
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
		if (trim( $this->product_name ) == '') {
			$this->setError( 'Please enter the product name.' );
			return false;
		}
		if (trim( $this->sku ) == '') {
			$this->setError( 'Please enter the product sku.' );
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
	 * Method first checks for a new image, then loads from the
	 * old framework
	 */
	function getImage()
	{
		$db =& $this->getDBO();

		$query = 'SELECT *'
		. ' FROM images'
		. ' WHERE itemid = '.$this->id;
		$db->setQuery( $query );
		
		if ($result = $db->loadAssoc( ))
			return url($result['filepath'].$result['file']);
		
		elseif ($this->img)
			return url($this->img);
			
		else
			return url('five-contents/uploads/product/no_product.png');
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
	
	/**
	 * Method is responsible for generating a secToken if it doesn't
	 * exist.
	 * 
	 */
	function getSecToken()
	{
		if (!$this->secToken)
		{
			$this->secToken = create_guid();
			$this->store();
		}
		
		return $this->secToken;
	}

	/**
	 * Returns a property of the object or the default value if the property is not set.
	 *
	 * @access	public
	 * @param	string $property The name of the property
	 * @param	mixed  $default The default value
	 * @return	mixed The value of the property
	 * @see		getProperties()
	 * @since	1.5
 	 */
	function get($property, $default=null)
	{
		if(isset($this->$property) && $property == 'secToken') {
			return $this->getSecToken();
		}
		elseif(isset($this->$property)) {
			return $this->$property;
		}
		return $default;
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
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function getLike( $search=null, $k='prod_name' )
	{
		if ($search === null) {
			return array();
		}
		
		$db =& $this->getDBO();

		$query = 'SELECT *'
		. ' FROM '.$this->_tbl
		. " WHERE $k LIKE '%$search%'";
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
	
	/**
	 * Method is responsible for loading the proper sql system
	 * 
	 * @param unknown_type $catName
	 * @param unknown_type $colName
	 * @param unknown_type $asstName
	 * @return Ambigous <unknown, string, unknown>|Ambigous <string, unknown>|unknown
	 */
	function getProducts( $catName=null, $colName=null, $asstName=null )
	{
		if (!$catName && !$colName && !$asstName)
		{
			return $this->getAllProducts();
		}
		
		elseif ($catName && !$colName && !$asstName)
		{
			return $this->getCategoryProducts( $catName );
		}
		
		elseif ($catName && $colName && !$asstName)
		{
			return $this->getCollectionProducts( $catName, $colName );
		}
		
		elseif ($catName && $colName && $asstName)
		{
			return $this->getAssortmentProducts( $catName, $colName, $asstName );
		}
	}
	
	/**
	 * Get products by assortments
	 * 
	 * @param $catName
	 * @param $colName
	 * @param $asstName
	 */
	function getAssortmentProducts( $catName=null, $colName=null, $asstName=null )
	{
		$category = FiveTable::getInstance('categories');
		$category->loadByName( urldecode($catName) );
		
		$collection = FiveTable::getInstance('collections');
		$collection->loadByParentAndName(urldecode($catName), urldecode($colName));
		
		$cacid = get_cacid($category->id,$collection->id);
		
		$assortment = FiveTable::getInstance('assortments');
		$assortment->loadByParentParentAndName(urldecode($catName), urldecode($colName), urldecode($asstName));
		
		$ccaid = get_ccaid($cacid, $assortment->id);
		
		//echo _520($collection);
		$db =& $this->getDBO();
		$db->resetTree();
		
		$db->setTree('SELECT', '`'.$this->_tbl.'`.*');
		$db->setTree('FROM', '`#__rel_prod_cca`');
		$db->setTree('INNER JOIN', '`'.$this->_tbl.'` ON `#__rel_prod_cca`.prodid='.$this->_tbl.'.id');
		$db->setTree('WHERE', '`'.$this->_tbl.'`.`status`='.$db->Quote('active'));
		$db->setTree('WHERE', '`#__rel_prod_cca`.ccaid = '.$db->Quote($ccaid));
		
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
			$db->setTree('ORDER BY', "`prod_name` ASC");
		
		$per_page = config('products.per_page');
		$db->setTree('LIMIT', $per_page);
		
		if ($offset = (BRequest::getInt('ppage',1)-1) * $per_page) {
			$db->setTree('OFFSET', $offset);
		}
		
		$q = $db->queryTree();
		//echo _520($assortment);
		if ($results = $db->loadList( $this->_tbl_key, $this->_type )) {
			return $results;
		}
		return false;
	}
	
	/**
	 * get collections products
	 * 
	 * @param unknown_type $catName
	 * @param unknown_type $colName
	 * @return unknown|string
	 */
	function getCollectionProducts( $catName=null, $colName=null )
	{
		$category = FiveTable::getInstance('categories');
		$category->loadByName( urldecode($catName) );
		
		$collection = FiveTable::getInstance('collections');
		$collection->loadByParentAndName(urldecode($catName), urldecode($colName));
		
		$cacid = get_cacid($category->id,$collection->id);
		//echo _520($collection);
		$db =& $this->getDBO();
		$db->resetTree();
		
		$db->setTree('SELECT', '`'.$this->_tbl.'`.*');
		$db->setTree('FROM', '`#__rel_prod_cca`');
		$db->setTree('INNER JOIN', '`'.$this->_tbl.'` ON `#__rel_prod_cca`.prodid='.$this->_tbl.'.id');
		$db->setTree('WHERE', '`'.$this->_tbl.'`.`status`='.$db->Quote('active'));
		$db->setTree('WHERE', '`#__rel_prod_cca`.cacid = '.$db->Quote($cacid));
		
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
			$db->setTree('ORDER BY', "`prod_name` ASC");
		
		$per_page = config('products.per_page');
		$db->setTree('LIMIT', $per_page);
		
		if ($offset = (BRequest::getInt('ppage',1)-1) * $per_page) {
			$db->setTree('OFFSET', $offset);
		}
		
		$q = $db->queryTree();
		if ($results = $db->loadList( $this->_tbl_key, $this->_type )) {
			return $results;
		}
		return false;
	}
	
	/**
	 * method is responsible for loading products from a category
	 * @param $catName
	 */
	function getCategoryProducts( $catName=null )
	{
		$category = FiveTable::getInstance('categories');
		$category->loadByName( $catName );
	
		$db =& $this->getDBO();
		$db->resetTree();
		
		$db->setTree('SELECT', '`'.$this->_tbl.'`.*');
		$db->setTree('FROM', '`#__rel_prod_cca`');
		$db->setTree('INNER JOIN', '`'.$this->_tbl.'` ON `#__rel_prod_cca`.prodid='.$this->_tbl.'.id');
		$db->setTree('WHERE', '`'.$this->_tbl.'`.`status`='.$db->Quote('active'));
		$db->setTree('WHERE', '`#__rel_prod_cca`.catid = '.$db->Quote($category->id));
		
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
			$db->setTree('ORDER BY', "`prod_name` ASC");
		
		$per_page = config('products.per_page');
		$db->setTree('LIMIT', $per_page);
		
		if ($offset = (BRequest::getInt('ppage',1)-1) * $per_page) {
			$db->setTree('OFFSET', $offset);
		}
		
		$q = $db->queryTree();
		if ($results = $db->loadList( $this->_tbl_key, $this->_type )) {
			return $results;
		}
		return false;
	}
	
	/**
	 * Function is responsible for listing the ALL of the products from
	 * the database without querying for any categories.
	 * 
	 * @return unknown|string
	 */
	function getAllProducts()
	{
		$db =& $this->getDBO();
		$db->resetTree();
		
		$db->setTree('SELECT', '`'.$this->_tbl.'`.*');
		$db->setTree('FROM', $this->_tbl);
		$db->setTree('WHERE', '`'.$this->_tbl.'`.`status`='.$db->Quote('active'));
		
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
			$db->setTree('ORDER BY', "`prod_name` ASC");
		
		$per_page = config('products.per_page');
		$db->setTree('LIMIT', $per_page);
		
		if ($offset = (BRequest::getInt('ppage',1)-1) * $per_page) {
			$db->setTree('OFFSET', $offset);
		}
		
		$q = $db->queryTree();
		if ($results = $db->loadList( $this->_tbl_key, $this->_type )) {
			return $results;
		}
		return false;
	}
}