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
 * Simple Record Set object to allow our database connector to be used with
 * ADODB driven 3rd party libraries
 *
 * @subpackage	Database
 * @since		1.5
 */
class FiveRecordSet
{
	/** @var array */
	var $data	= null;
	/** @var int Index to current record */
	var $pointer= null;
	/** @var int The number of rows of data */
	var $count	= null;

	/**
	 * Constuctor
	 * @param array
	 */
	function FiveRecordSet( $data )
	{
		$this->data = $data;
		$this->pointer = 0;
		$this->count = count( $data );
	}
	/**
	 * @return int
	 */
	function RecordCount() {
		return $this->count;
	}
	
	/**
	 * @return int
	 */
	function RowCount() {
		return $this->RecordCount();
	}
	
	/**
	 * @return mixed A row from the data array or null
	 */
	function FetchRow()
	{
		if ($this->pointer < $this->count) {
			$result = $this->data[$this->pointer];
			$this->pointer++;
			return $result;
		} else {
			return null;
		}
	}
	/**
	 * @return array
	 */
	function GetRows() {
		return $this->data;
	}
	/**
	 * TODO: Remove for 1.6.  Deprecated
	 */
	function absolutepage() {
		return 1;
	}
	/**
	 * TODO: Remove for 1.6.  Deprecated
	 */
	function atfirstpage() {
		return 1;
	}
	/**
	 * TODO: Remove for 1.6.  Deprecated
	 */
	function atlastpage() {
		return 1;
	}
	/**
	 * TODO: Remove for 1.6.  Deprecated
	 */
	function lastpageno() {
		return 1;
	}
	/**
	 * TODO: Remove for 1.6.  Deprecated
	 */
	function Close() {
	}
}