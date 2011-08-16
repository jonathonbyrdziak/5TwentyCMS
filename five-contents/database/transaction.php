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
class FiveTableTransaction extends FiveTable
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
	var $bizid			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $orderid		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $featid			= null;

	/**
	 * Unique id
	 *
	 * @var int
	 */
	var $cs_reqid		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $cs_reqToken	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $amt_PM			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $amt_PM_fee		= null;

	/**
	 * Unique id
	 *
	 * @var int
	 */
	var $amt_BIZ		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $amt_subtotal	= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $amt_tax		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $amt_shipping	= null;

	/**
	 * Unique id
	 *
	 * @var int
	 */
	var $amt_total		= null;

	/**
	 * invoice, order, service_reg, vendor_reg
	 *
	 * @var string
	 */
	var $type 			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $date_transaction = null;

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
	var $_ccName		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $_ccType		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $_ccNum			= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $_ccExpMM		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $_ccExpYY		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $_ccSecCode		= null;

	/**
	 * 
	 *
	 * @var string
	 */
	var $_promocode		= null;

	/**
	* @param database A database connector object
	*/
	function __construct( &$db )
	{
		parent::__construct( '#__transactions', 'id', $db );

		//initialise
		$this->id		= 0;
		$this->userid	= 0;
		$this->bizid	= 0;
		$this->status	= 'pending';
	}

	/**
	 * Validation and filtering
	 *
	 * @return boolean True is satisfactory
	 */
	function check()
	{
		// Validate user information
		if (trim( $this->_ccName ) == '') {
			$this->setError( 'Please enter your name as it appears on your credit card.' );
			return false;
		}
		
		if (!checkCreditCard($this->_ccNum, $this->_ccType)) {
			global $errortext;
			$this->setError( $errortext );
			return false;
		}
		
		$now =& FiveFactory::getDate();
		if ($now->isAfter($this->_ccExpYY.'-'.$this->_ccExpMM.'-28')) {
			$this->setError( "Your credit card has expired." );
			return false;
		}
		
		if ($this->date_transaction == null) {
			// Set the registration timestamp
			$now =& FiveFactory::getDate();
			$this->date_transaction = strtotime($now->toMySQL());
		}

		return true;
	}
}