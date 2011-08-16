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
 * Abstract Table class
 *
 * Parent classes to all tables.
 *
 * @abstract
 * @subpackage	Table
 * @since		1.0
 */
class FiveTable extends Object
{
	/**
	 * Name of the table in the db schema relating to child class
	 *
	 * @var 	string
	 * @access	protected
	 */
	var $_tbl		= '';

	/**
	 * Name of the primary key field in the table
	 *
	 * @var		string
	 * @access	protected
	 */
	var $_tbl_key	= '';

	/**
	 * Database connector
	 *
	 * @var		FiveDatabase
	 * @access	protected
	 */
	var $_db		= null;

	/**
	 * Object constructor to set table and key field
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @access protected
	 * @param string $table name of the table in the db schema relating to child class
	 * @param string $key name of the primary key field in the table
	 * @param object $db FiveDatabase object
	 */
	function __construct( $table, $key, &$db )
	{
		$this->_tbl		= $table;
		$this->_tbl_key	= $key;
		$this->_db		=& $db;
	}

	/**
	 * Returns a reference to the a Table object, always creating it
	 *
	 * @param type 		$type 	 The table type to instantiate
	 * @param string 	$prefix	 A prefix for the table class name. Optional.
	 * @param array		$options Configuration array for model. Optional.
	 * @return database A database object
	 * @since 1.5
	*/
	function &getInstance( $type, $prefix = 'FiveTable', $config = array() )
	{
		$false = false;

		$type = preg_replace('/[^A-Z0-9_\.-]/i', '', $type);
		$tableClass = $prefix.ucfirst($type);

		if (!class_exists( $tableClass ))
		{
			if($path = FivePath::find(FiveTable::addIncludePath(), strtolower($type).'.php'))
			{
				require_once $path;

				if (!class_exists( $tableClass ))
				{
					trigger_error('Table class ' . $tableClass . ' not found in file.', E_USER_WARNING );
					return $false;
				}
			}
			else
			{
				trigger_error( 'Table ' . $type . ' not supported. File not found.', E_USER_WARNING );
				return $false;
			}
		}

		//Make sure we are returning a DBO object
		if (array_key_exists('dbo', $config))  {
			$db =& $config['dbo'];
		} else {
			$db = & FiveFactory::getDBO();
		}

		$instance = new $tableClass($db);
		$instance->_type = $type;

		return $instance;
	}

	/**
	 * Get the internal database object
	 *
	 * @return object A FiveDatabase based object
	 */
	function &getDBO()
	{
		return $this->_db;
	}

	/**
	 * Set the internal database object
	 *
	 * @param	object	$db	A FiveDatabase based object
	 * @return	void
	 */
	function setDBO(&$db)
	{
		$this->_db =& $db;
	}

	/**
	 * Gets the internal table name for the object
	 *
	 * @return string
	 * @since 1.5
	 */
	function getTableName()
	{
		return $this->_tbl;
	}

	/**
	 * Gets the internal primary key name
	 *
	 * @return string
	 * @since 1.5
	 */
	function getKeyName()
	{
		return $this->_tbl_key;
	}

	/**
	 * Resets the default properties
	 * @return	void
	 */
	function reset()
	{
		$k = $this->_tbl_key;
		foreach ($this->getProperties() as $name => $value)
		{
			if($name != $k)
			{
				$this->$name	= $value;
			}
		}
	}

	/**
	 * Binds a named array/hash to this object
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @access	public
	 * @param	$from	mixed	An associative array or object
	 * @param	$ignore	mixed	An array or space separated list of fields not to bind
	 * @return	boolean
	 */
	function bind( $from, $ignore=array(), $public = true )
	{
		$fromArray	= is_array( $from );
		$fromObject	= is_object( $from );

		if (!$fromArray && !$fromObject)
		{
			$this->setError( get_class( $this ).'::bind failed. Invalid from argument' );
			return false;
		}
		if (!is_array( $ignore )) {
			$ignore = explode( ' ', $ignore );
		}
		foreach ($this->getProperties($public) as $k => $v)
		{
			// internal attributes of an object are ignored
			if (!in_array( $k, $ignore ))
			{
				if ($fromArray && isset( $from[$k] )) {
					$this->$k = $from[$k];
				} else if ($fromObject && isset( $from->$k )) {
					$this->$k = $from->$k;
				}
			}
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
	function load( $oid=null )
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
		. ' WHERE '.$this->_tbl_key.' = '.$db->Quote($oid);
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
	function loadByUserID( $oid=null )
	{
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

	/**
	 * Loads a row from the database and binds the fields to the object properties
	 *
	 * @access	public
	 * @param	mixed	Optional primary key.  If not specifed, the value of current key is used
	 * @return	boolean	True if successful
	 */
	function loadAll( $query=null )
	{
		$this->reset();
		
		$db =& $this->getDBO();
		
		if (is_null($query))
		{
			$query = 'SELECT *'
			. ' FROM '.$this->_tbl;
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
	 * Generic check method
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @access public
	 * @return boolean True if the object is ok
	 */
	function check()
	{
		return true;
	}

	/**
	 * Inserts a new row if id is zero or updates an existing row in the database table
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @access public
	 * @param boolean If false, null object variables are not updated
	 * @return null|string null if successful otherwise returns and error message
	 */
	function store( $updateNulls=false )
	{
		$k = $this->_tbl_key;

		if( $this->$k)
		{
			$ret = $this->_db->updateObject( $this->_tbl, $this, $this->_tbl_key, $updateNulls );
		}
		else
		{
			$ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
		}
		if( !$ret )
		{
			$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Description
	 *
	 * @access public
	 * @param $dirn
	 * @param $where
	 */
	function move( $dirn, $where='' )
	{
		if (!in_array( 'ordering',  array_keys($this->getProperties())))
		{
			$this->setError( get_class( $this ).' does not support ordering' );
			return false;
		}

		$k = $this->_tbl_key;

		$sql = "SELECT $this->_tbl_key, ordering FROM $this->_tbl";

		if ($dirn < 0)
		{
			$sql .= ' WHERE ordering < '.(int) $this->ordering;
			$sql .= ($where ? ' AND '.$where : '');
			$sql .= ' ORDER BY ordering DESC';
		}
		else if ($dirn > 0)
		{
			$sql .= ' WHERE ordering > '.(int) $this->ordering;
			$sql .= ($where ? ' AND '. $where : '');
			$sql .= ' ORDER BY ordering';
		}
		else
		{
			$sql .= ' WHERE ordering = '.(int) $this->ordering;
			$sql .= ($where ? ' AND '.$where : '');
			$sql .= ' ORDER BY ordering';
		}

		$this->_db->setQuery( $sql, 0, 1 );


		$row = null;
		$row = $this->_db->loadObject();
		if (isset($row))
		{
			$query = 'UPDATE '. $this->_tbl
			. ' SET ordering = '. (int) $row->ordering
			. ' WHERE '. $this->_tbl_key .' = '. $this->_db->Quote($this->$k)
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->query())
			{
				$err = $this->_db->getErrorMsg();
				triggr_error( $err, E_USER_WARNING );
			}

			$query = 'UPDATE '.$this->_tbl
			. ' SET ordering = '.(int) $this->ordering
			. ' WHERE '.$this->_tbl_key.' = '.$this->_db->Quote($row->$k)
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->query())
			{
				$err = $this->_db->getErrorMsg();
				triggr_error( $err, E_USER_WARNING );
			}

			$this->ordering = $row->ordering;
		}
		else
		{
			$query = 'UPDATE '. $this->_tbl
			. ' SET ordering = '.(int) $this->ordering
			. ' WHERE '. $this->_tbl_key .' = '. $this->_db->Quote($this->$k)
			;
			$this->_db->setQuery( $query );

			if (!$this->_db->query())
			{
				$err = $this->_db->getErrorMsg();
				triggr_error( $err, E_USER_WARNING );
			}
		}
		return true;
	}

	/**
	 * Returns the ordering value to place a new item last in its group
	 *
	 * @access public
	 * @param string query WHERE clause for selecting MAX(ordering).
	 */
	function getNextOrder ( $where='' )
	{
		if (!in_array( 'ordering', array_keys($this->getProperties()) ))
		{
			$this->setError( get_class( $this ).' does not support ordering' );
			return false;
		}

		$query = 'SELECT MAX(ordering)' .
				' FROM ' . $this->_tbl .
				($where ? ' WHERE '.$where : '');

		$this->_db->setQuery( $query );
		$maxord = $this->_db->loadResult();

		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $maxord + 1;
	}

	/**
	 * Compacts the ordering sequence of the selected records
	 *
	 * @access public
	 * @param string Additional where query to limit ordering to a particular subset of records
	 */
	function reorder( $where='' )
	{
		$k = $this->_tbl_key;

		if (!in_array( 'ordering', array_keys($this->getProperties() ) ))
		{
			$this->setError( get_class( $this ).' does not support ordering');
			return false;
		}

		$query = 'SELECT '.$this->_tbl_key.', ordering'
		. ' FROM '. $this->_tbl
		. ' WHERE ordering >= 0' . ( $where ? ' AND '. $where : '' )
		. ' ORDER BY ordering'
		;
		$this->_db->setQuery( $query );
		if (!($orders = $this->_db->loadObjectList()))
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		// compact the ordering numbers
		for ($i=0, $n=count( $orders ); $i < $n; $i++)
		{
			if ($orders[$i]->ordering >= 0)
			{
				if ($orders[$i]->ordering != $i+1)
				{
					$orders[$i]->ordering = $i+1;
					$query = 'UPDATE '.$this->_tbl
					. ' SET ordering = '. (int) $orders[$i]->ordering
					. ' WHERE '. $k .' = '. $this->_db->Quote($orders[$i]->$k)
					;
					$this->_db->setQuery( $query);
					$this->_db->query();
				}
			}
		}

	return true;
	}

	/**
	 * Generic check for whether dependancies exist for this object in the db schema
	 *
	 * can be overloaded/supplemented by the child class
	 *
	 * @access public
	 * @param string $msg Error message returned
	 * @param int Optional key index
	 * @param array Optional array to compiles standard joins: format [label=>'Label',name=>'table name',idfield=>'field',joinfield=>'field']
	 * @return true|false
	 */
	function canDelete( $oid=null, $joins=null )
	{
		$k = $this->_tbl_key;
		if ($oid) {
			$this->$k = intval( $oid );
		}

		if (is_array( $joins ))
		{
			$select = "$k";
			$join = "";
			foreach( $joins as $table )
			{
				$select .= ', COUNT(DISTINCT '.$table['idfield'].') AS '.$table['idfield'];
				$join .= ' LEFT JOIN '.$table['name'].' ON '.$table['joinfield'].' = '.$k;
			}

			$query = 'SELECT '. $select
			. ' FROM '. $this->_tbl
			. $join
			. ' WHERE '. $k .' = '. $this->_db->Quote($this->$k)
			. ' GROUP BY '. $k
			;
			$this->_db->setQuery( $query );

			if (!$obj = $this->_db->loadObject())
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$msg = array();
			$i = 0;
			foreach( $joins as $table )
			{
				$k = $table['idfield'] . $i;
				if ($obj->$k)
				{
					$msg[] = JText::_( $table['label'] );
				}
				$i++;
			}

			if (count( $msg ))
			{
				$this->setError("noDeleteRecord" . ": " . implode( ', ', $msg ));
				return false;
			}
			else
			{
				return true;
			}
		}

		return true;
	}

	/**
	 * Default delete method
	 *
	 * can be overloaded/supplemented by the child class
	 *
	 * @access public
	 * @return true if successful otherwise returns and error message
	 */
	function delete( $oid=null )
	{
		//if (!$this->canDelete( $msg ))
		//{
		//	return $msg;
		//}

		$k = $this->_tbl_key;
		if ($oid) {
			$this->$k = intval( $oid );
		}

		$query = 'DELETE FROM '.$this->_db->nameQuote( $this->_tbl ).
				' WHERE '.$this->_tbl_key.' = '. $this->_db->Quote($this->$k);
		$this->_db->setQuery( $query );

		if ($this->_db->query())
		{
			return true;
		}
		else
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	}

	/**
	 * Description
	 *
	 * @access public
	 * @param $oid
	 * @param $log
	 */
	function hit( $oid=null, $log=false )
	{
		if (!in_array( 'hits', array_keys($this->getProperties()) )) {
			return;
		}

		$k = $this->_tbl_key;

		if ($oid !== null) {
			$this->$k = intval( $oid );
		}

		$query = 'UPDATE '. $this->_tbl
		. ' SET hits = ( hits + 1 )'
		. ' WHERE '. $this->_tbl_key .'='. $this->_db->Quote($this->$k);
		$this->_db->setQuery( $query );
		$this->_db->query();
		$this->hits++;
	}

	/**
	 * Generic save function
	 *
	 * @access	public
	 * @param	array	Source array for binding to class vars
	 * @param	string	Filter for the order updating
	 * @param	mixed	An array or space separated list of fields not to bind
	 * @returns TRUE if completely successful, FALSE if partially or not succesful.
	 */
	function save( $source, $order_filter='', $ignore='' )
	{
		if (!$this->bind( $source, $ignore )) {
			return false;
		}
		if (!$this->check()) {
			return false;
		}
		if (!$this->store()) {
			return false;
		}
		if ($order_filter)
		{
			$filter_value = $this->$order_filter;
			$this->reorder( $order_filter ? $this->_db->nameQuote( $order_filter ).' = '.$this->_db->Quote( $filter_value ) : '' );
		}
		$this->setError('');
		return true;
	}

	/**
	 * Export item list to xml
	 *
	 * @access public
	 * @param boolean Map foreign keys to text values
	 */
	function toXML( $mapKeysToText=false )
	{
		$xml = '<record table="' . $this->_tbl . '"';

		if ($mapKeysToText)
		{
			$xml .= ' mapkeystotext="true"';
		}
		$xml .= '>';
		foreach (get_object_vars( $this ) as $k => $v)
		{
			if (is_array($v) or is_object($v) or $v === NULL)
			{
				continue;
			}
			if ($k[0] == '_')
			{ // internal field
				continue;
			}
			$xml .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
		}
		$xml .= '</record>';

		return $xml;
	}

	/**
	 * Add a directory where FiveTable should search for table types. You may
	 * either pass a string or an array of directories.
	 *
	 * @access	public
	 * @param	string	A path to search.
	 * @return	array	An array with directory elements
	 * @since 1.5
	 */
	function addIncludePath( $path=null )
	{
		static $paths;

		if (!isset($paths)) {
			$paths = array( CONTENTS.DS.'database' );
		}

		// just force path to array
		settype($path, 'array');

		if (!empty( $path ) && !in_array( $path, $paths ))
		{
			// loop through the path directories
			foreach ($path as $dir)
			{
				// no surrounding spaces allowed!
				$dir = trim($dir);

				// add to the top of the search dirs
				// so that custom paths are searched before core paths
				array_unshift($paths, $dir);
			}
		}
		return $paths;
	}
}