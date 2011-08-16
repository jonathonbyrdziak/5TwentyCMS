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
class FiveTableUser extends FiveTable
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
	 * The email
	 *
	 * @var string
	 */
	var $email			= null;

	/**
	 * The login name
	 *
	 * @var string
	 */
	var $username		= null;

	/**
	 * The users real name (or nickname)
	 *
	 * @var string
	 */
	var $firstName		= null;
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $mi				= null;

	/**
	 * The users real name (or nickname)
	 *
	 * @var string
	 */
	var $lastName		= null;

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
	var $mainPhone		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $altPhone		= null;
	
	/**
	 * MD5 encrypted password
	 *
	 * @var string
	 */
	var $password		= null;
	
	/**
	 * 
	 *
	 * @var string
	 */
	var $cs_requestid	= null;

	/**
	 * Description
	 *
	 * @var int
	 */
	var $cs_requesttoken= null;

	/**
	 * Description
	 *
	 * @var int
	 */
	var $secToken		= null;

	/**
	 * 
	 *
	 * @var int
	 */
	var $flag_nl		= null;

	/**
	 * 
	 *
	 * @var int
	 */
	var $flag_tosu		= null;

	/**
	 * Description
	 *
	 * @var datetime
	 */
	var $dateReg		= null;

	/**
	 * Description
	 *
	 * @var datetime
	 */
	var $lastvisitDate	= null;

	/**
	 * Description
	 *
	 * @var string activation hash
	 */
	var $activation		= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $params			= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $status			= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $statement		= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $name			= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $title			= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $urlSite		= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $urlBlog		= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $urlFb			= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $urlTwtr		= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $urlLi			= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $urlYt			= null;

	/**
	 * Description
	 *
	 * @var string
	 */
	var $avatar			= null;

	/**
	 * FiveTableGroups Object
	 *
	 * @var string
	 */
	var $_groups		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $_xpass		= null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__user', 'id', $db );
		
		//initialise
		$this->id        = 0;
		$this->loadGroup();
	}

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function load( $oid=null )
	{
		if($load = parent::load($oid)) {
			$this->loadGroup();
		}
		
		$this->_xpass = $this->password;
		
		return $load;
	}
	
	/**
	 * Function is responsible for making sure that the avatar is saved
	 * 
	 * (non-PHPdoc)
	 * @see FTP2.FTPTOYOURSITE.COM/shop.saturdaymarket.org/web/content/five-includes/database/FiveTable#store()
	 */
	function store( $updateNulls=false )
	{
		if ($avatar = BRequest::getVar('avatar', false, 'files')) {
			//delete the old
			if (!is_null($this->avatar)) @unlink($this->avatar);
			//save the new
			$this->avatar = save_file($avatar, 'image', 'avatar');
		}
		
		if (parent::store( $updateNulls )) {
			$this->loadGroup();
			$this->_groups->store();
		}
	}
	
	/**
	 * Function is responsible for loading the group class if it hasn't already been loaded.
	 */
	function loadGroup()
	{
		if (!$this->_groups) {
			$this->_groups = FiveTable::getInstance('groups');
		}
		
		if(!isset($this->_groups->id) || !$this->_groups->id) {
			$this->_groups->load( $this->id );
			@$_SESSION['user']['group']['admin'] = $this->_groups->admin;
			@$_SESSION['user']['group']['business'] = $this->_groups->business;
			@$_SESSION['user']['group']['user'] = $this->_groups->user;
		}
		
		return true;
	}
	
	/**
	 * Function is responsible for knowing if this user is a business
	 */
	function is_business()
	{
		if ($this->_groups->business) return true;
		return false;
	}
	
	/**
	 * Function is responsible for knowing if this user is a business
	 */
	function is_admin()
	{
		if ($this->_groups->admin) return true;
		return false;
	}
	
	/**
	 * Function is responsible for knowing if this user is a business
	 */
	function is_user()
	{
		if ($this->_groups->user) return true;
		return false;
	}

	/**
	 * Validation and filtering
	 *
	 * @return boolean True is satisfactory
	 */
	function check()
	{
		// Validate user information
		if (trim( $this->firstName ) == '') {
			$this->setError( 'Please enter your name.' );
			return false;
		}
		
		if ((trim($this->email) == "") || ! is_email($this->email) ) {
			$this->setError( 'Please enter a valid Email address' );
			return false;
		}
		
		if (trim( $this->username ) == '') {
			$this->username = preg_replace( "#[<>\"'%;()&\@\.]#i", '', $this->email );
		}
		
		if (trim( $this->username ) == '' || strlen(utf8_decode($this->username )) < 2) {
			$this->setError( 'Please enter a valid Email address.' );
			return false;
		}
		
		// attempting to update our password
		if (BRequest::getVar('_xpassword', false) || BRequest::getVar('password', false) && BRequest::getVar('password', '') != '')
		{
			if (BRequest::getVar('_xpassword') != $this->password)
			{
				$this->setError( 'Please confirm your password change.' );
				return false;
			}
			else
			{
				//password change is good
				$this->password = pw_encode($this->password);
			}
		}
		
		
		if (!$this->password)
			$this->password = $this->_xpass;
		
		
		if ($this->dateReg == null) {
			// Set the registration timestamp
			$now =& FiveFactory::getDate();
			$this->dateReg = $now->toMySQL();
		}
		

		// check for existing username
		$query = 'SELECT id'
		. ' FROM '. $this->_tbl
		. ' WHERE username = ' . $this->_db->Quote($this->username)
		. ' AND id != '. (int) $this->id;
		;
		$this->_db->setQuery( $query );
		$xid = intval( $this->_db->loadResult() );
		if ($xid && $xid != intval( $this->id )) {
			$this->setError( 'Username is already in use.' );
			return false;
		}


		// check for existing email
		$query = 'SELECT id'
			. ' FROM '. $this->_tbl
			. ' WHERE email = '. $this->_db->Quote($this->email)
			. ' AND id != '. (int) $this->id
			;
		$this->_db->setQuery( $query );
		$xid = intval( $this->_db->loadResult() );
		if ($xid && $xid != intval( $this->id )) {
			$this->setError( 'Email is already in use.' );
			return false;
		}

		return true;
	}

	/**
	 * Validation and filtering
	 * 
	 * @return boolean True is satisfactory
	 */
	function authenticate($username, $password)
	{
		//Checking the users email
		if (is_email($username)) 
		{
			$this->email = $username;
			
			if ((trim($this->email) == "") || ! is_email($this->email) ) {
				$this->setError( "The entered Email is invalid." );
				return false;
			}
	
			// check for existing email
			$query = 'SELECT id, password'
				. ' FROM '. $this->_tbl
				. ' WHERE email = '. $this->_db->Quote($this->email)
				. ' AND id != '. (int) $this->id
				;
			$this->_db->setQuery( $query );
			
			if (!$result = $this->_db->loadAssoc()) {
				$this->setError( "Sorry, the Email and Password entered did not match our records." );
				return false;
			}
		}
		
		//checking the users username
		else 
		{
			$this->username = $username;
			
			// Validate user information
			if (trim( $this->username ) == '' && trim( $this->email ) == '') {
				$this->setError( "Please enter your Username or Email address." );
				return false;
			}
	
			if (preg_match( "#[<>\"'%;()&]#i", $this->username) || strlen(utf8_decode($this->username )) < 2) {
				$this->setError( "The entered Username is invalid." );
				return false;
			}
	
			// check for existing username
			$query = 'SELECT id, password'
			. ' FROM '. $this->_tbl
			. ' WHERE username = ' . $this->_db->Quote($this->username)
			. ' AND id != '. (int) $this->id;
			;
			$this->_db->setQuery( $query );
			
			if (!$result = $this->_db->loadAssoc()) {
				$this->setError( "Sorry, the Username and Password entered did not match our records." );
				return false;
			}
			
		}
		
		//checking the users password
		$this->password = $password;
		
		if (!pw_check($this->password, $result['password'])) {
			$this->setError( "Sorry, the Username and Password entered did not match our records." );
			return false;
		}
		
		$this->load($result['id']);
		$this->setLastVisit();
		set_session($this->id, $this->username, $this->email, $this->zip);
		return true;
	}
	
	/**
	 * Function is responsible for sending out the registration email
	 *
	 */
	function setRegistrationToken()
	{
		if (is_null($this->secToken)) {
			$this->secToken = sha1(time().rand(0,9999999).$this->id);
			$this->store();
		}
		return $this->secToken;
	}

	/**
	 * Updates last visit time of user
	 *
	 * @param int The timestamp, defaults to 'now'
	 * @return boolean False if an error occurs
	 */
	function setLastVisit( $timeStamp=null, $id=null )
	{
		// check for User ID
		if (is_null( $id )) {
			if (isset( $this )) {
				$id = $this->id;
			} else {
				// do not translate
				exit( 'WARNUSER' );
			}
		}

		// if no timestamp value is passed to functon, than current time is used
		$date =& FiveFactory::getDate($timeStamp);

		// updates user lastvistdate field with date and time
		$query = 'UPDATE '. $this->_tbl
		. ' SET lastvisitDate = '.$this->_db->Quote($date->toMySQL())
		. ' WHERE id = '. (int) $id
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}

		return true;
	}
}