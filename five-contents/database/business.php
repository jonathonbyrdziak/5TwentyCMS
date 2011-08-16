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
class FiveTableBusiness extends FiveTable
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
	var $import_id		= null;
	
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
	var $secToken		= null;

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
	var $name			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $desc			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $addr1			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $addr2			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $city			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $state			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $zip			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $urlPM			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $urlSite		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $urlBlog		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $urlFb			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $urlTwtr		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $urlLi			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $urlYt			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_SS		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_nl		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_ag		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_franchise	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $flag_feat		= null;

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
	var $dateCreated	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $dateCCapproved	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $dateExpires	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $dateDeclined	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $status			= null;

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
	var $bio		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $slogan			= null;

	/**
	 * Banner Image
	 *
	 * @var string
	 */
	var $banner			= null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__business', 'id', $db );

		//initialise
		$this->id		= 0;
		$this->userid	= 0;
		$this->status	= 'submit';
	}

	/**
	 * Saving this
	 *
	 * @return boolean True is satisfactory
	 */
	function store( $updateNulls=false )
	{
		if ($banner = BRequest::getVar('img', false, 'files')) {
			//delete the old
			if (!is_null($this->banner)) @unlink($this->banner);
			//save the new
			$this->banner = save_file($banner, 'image', 'banner');
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
		if (trim( $this->name ) == '') {
			$this->setError( 'Please enter the company name.' );
			return false;
		}
		if (trim($this->addr1) == "" || trim($this->city) == "" || trim($this->state) == "" || trim($this->zip) == "") {
			$this->setError( 'Please enter a valid address.' );
			return false;
		}
		
		if ($this->dateCreated == null) {
			// Set the registration timestamp
			$now =& FiveFactory::getDate();
			$this->dateCreated = strtotime($now->toMySQL());
		}

		return true;
	}
	
	/**
	 * Method is responsible for randomizing the featured businesses
	 * 
	 */
	function loadRandomFeatured()
	{
		$featured = $this->loadFeatured();
		if (!is_array($featured)) return false;
		
		$rand_keys = array_rand($featured, 1);
		return $featured[$rand_keys[0]];
	}
	
	/**
	 * Method is responsible for loading the featured businesses
	 * 
	 */
	function loadFeatured()
	{
		$db =& $this->getDBO();
		$now =& FiveFactory::getDate();
		
		$query = 'SELECT *'
		. ' FROM '.$this->_tbl
		. ' WHERE '. strtotime($now->toMySQL()) 
		. ' BETWEEN `feat_start` and `feat_end`';
		$db->setQuery( $query );
//echo _520($query);
		
		if ($results = $db->loadList( $this->_tbl_key, $this->_type )) {
			return $results;
		}
	}

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadByUserID( $oid=null )
	{
		$k = 'userid';

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
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadByBizID( $oid=null )
	{
		$k = $this->_tbl_key;

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
	 * 
	 */
	function getUserTable()
	{
		static $user;
		
		if (!isset($user))
		{
			$user = FiveTable::getInstance('user');
			$user->load( $this->userid );
		}
		
		return $user;
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
	 * Method is responsible for returning the url for this business
	 * 
	 */
	function getUrl()
	{
		return url('business/'.$this->id.'/'.slug( $this->name ));
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
}