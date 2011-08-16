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
 * BRequest
 * 
 * Class is responsible for managing the global variables
 * 
 * @author byrd
 * @since 1.0
 */
class BRequest
{
		
	/**
	 * Gets the request method
	 *
	 * @return string
	 */
	function getMethod()
	{
		$method = strtoupper( $_SERVER['REQUEST_METHOD'] );
		return $method;
	}

	/**
	 * Gets the request method
	 * 
	 * @param $script
	 */
	function head( $script = false )
	{
		if (!$script) return false;
		
		$current = urldecode(BRequest::getVar('SCRIPTS', null, 'FILES'));
		
		if (strpos($current, $script) === false)
		{
			BRequest::setVar('SCRIPTS', $current."\n".urlencode($script), 'FILES');
		}
		return true;
	}

	/**
	 * Fetches and returns a given variable.
	 *
	 * The default behaviour is fetching variables depending on the
	 * current request method: GET and HEAD will result in returning
	 * an entry from $_GET, POST and PUT will result in returning an
	 * entry from $_POST.
	 *
	 * You can force the source by setting the $hash parameter:
	 *
	 *   post		$_POST
	 *   get		$_GET
	 *   files		$_FILES
	 *   cookie		$_COOKIE
	 *   env		$_ENV
	 *   server		$_SERVER
	 *   method		via current $_SERVER['REQUEST_METHOD']
	 *   default	$_REQUEST
	 *
	 * @static
	 * @param	string	$name		Variable name
	 * @param	string	$default	Default value if the variable does not exist
	 * @param	string	$hash		Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @param	string	$type		Return type for the variable, for valid values see {@link BFilterInput::clean()}
	 * @param	int		$mask		Filter mask for the variable
	 * @return	mixed	Requested variable
	 * @since	1.0
	 */
	function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0)
	{
		// Ensure hash and type are uppercase
		$hash = strtoupper( $hash );
		if ($hash === 'METHOD') 
		{
			$hash = strtoupper( $_SERVER['REQUEST_METHOD'] );
		}
		$type	= strtoupper( $type );
		$sig	= $hash.$type.$mask;

		// Get the input hash
		switch ($hash)
		{
			case 'GET' :
				$input = &$_GET;
				break;
			case 'POST' :
				$input = &$_POST;
				break;
			case 'FILES' :
				$input = &$_FILES;
				break;
			case 'COOKIE' :
				$input = &$_COOKIE;
				break;
			case 'SESSION' :
				$input = &$_SESSION;
				break;
			case 'ENV'    :
				$input = &$_ENV;
				break;
			case 'SERVER'    :
				$input = &$_SERVER;
				break;
			default:
				$input = &$_REQUEST;
				$hash = 'REQUEST';
				break;
		}

		if (isset($GLOBALS['_BREQUEST'][$name]['SET.'.$hash]) && ($GLOBALS['_BREQUEST'][$name]['SET.'.$hash] === true)) 
		{
			// Get the variable from the input hash
			$var = (isset($input[$name]) && $input[$name] !== null) ? $input[$name] : $default;
			$var = BRequest::_cleanVar($var, $mask, $type);
		}
		elseif (!isset($GLOBALS['_BREQUEST'][$name][$sig]))
		{
			if (isset($input[$name]) && $input[$name] !== null) 
			{
				// Get the variable from the input hash and clean it
				$var = BRequest::_cleanVar($input[$name], $mask, $type);

				// Handle magic quotes compatability
				if (get_magic_quotes_gpc() && ($var != $default) && ($hash != 'FILES')) 
				{
					$var = BRequest::_stripSlashesRecursive( $var );
				}

				$GLOBALS['_BREQUEST'][$name][$sig] = $var;
			}
			elseif ($default !== null) {
				// Clean the default value
				$var = BRequest::_cleanVar($default, $mask, $type);
			}
			else 
			{
				$var = $default;
			}
		} 
		else 
		{
			$var = $GLOBALS['_BREQUEST'][$name][$sig];
		}

		return $var;
	}

	/**
	 * Fetches and returns a given filtered variable. The integer
	 * filter will allow only digits to be returned. This is currently
	 * only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @static
	 * @param	string	$name		Variable name
	 * @param	string	$default	Default value if the variable does not exist
	 * @param	string	$hash		Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return	integer	Requested variable
	 * @since	1.0
	 */
	function getInt($name, $default = 0, $hash = 'default')
	{
		return BRequest::getVar($name, $default, $hash, 'int');
	}

	/**
	 * Fetches and returns a given filtered variable.  The float
	 * filter only allows digits and periods.  This is currently
	 * only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @static
	 * @param	string	$name		Variable name
	 * @param	string	$default	Default value if the variable does not exist
	 * @param	string	$hash		Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return	float	Requested variable
	 * @since	1.0
	 */
	function getFloat($name, $default = 0.0, $hash = 'default')
	{
		return BRequest::getVar($name, $default, $hash, 'float');
	}

	/**
	 * Fetches and returns a given filtered variable. The bool
	 * filter will only return true/false bool values. This is
	 * currently only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @static
	 * @param	string	$name		Variable name
	 * @param	string	$default	Default value if the variable does not exist
	 * @param	string	$hash		Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return	bool		Requested variable
	 * @since	1.0
	 */
	function getBool($name, $default = false, $hash = 'default')
	{
		return BRequest::getVar($name, $default, $hash, 'bool');
	}

	/**
	 * Fetches and returns a given filtered variable. The word
	 * filter only allows the characters [A-Za-z_]. This is currently
	 * only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @static
	 * @param	string	$name		Variable name
	 * @param	string	$default	Default value if the variable does not exist
	 * @param	string	$hash		Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return	string	Requested variable
	 * @since	1.0
	 */
	function getWord($name, $default = '', $hash = 'default')
	{
		return BRequest::getVar($name, $default, $hash, 'word');
	}

	/**
	 * Fetches and returns a given filtered variable. The cmd
	 * filter only allows the characters [A-Za-z0-9.-_]. This is
	 * currently only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @static
	 * @param	string	$name		Variable name
	 * @param	string	$default	Default value if the variable does not exist
	 * @param	string	$hash		Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
	 * @return	string	Requested variable
	 * @since	1.0
	 */
	function getCmd($name, $default = '', $hash = 'default')
	{
		return BRequest::getVar($name, $default, $hash, 'cmd');
	}

	/**
	 * Fetches and returns a given filtered variable. The string
	 * filter deletes 'bad' HTML code, if not overridden by the mask.
	 * This is currently only a proxy function for getVar().
	 *
	 * See getVar() for more in-depth documentation on the parameters.
	 *
	 * @static
	 * @param	string	$name		Variable name
	 * @param	string	$default	Default value if the variable does not exist
	 * @param	string	$hash		Where the var should come from (POST, GET, FILES, COOKIE, METHOD)
 	 * @param	int		$mask		Filter mask for the variable
	 * @return	string	Requested variable
	 * @since	1.0
	 */
	function getString($name, $default = '', $hash = 'default', $mask = 0)
	{
		// Cast to string, in case BREQUEST_ALLOWRAW was specified for mask
		return (string) BRequest::getVar($name, $default, $hash, 'string', $mask);
	}

	/**
	 * Set a variabe in on of the request variables
	 *
	 * @access	public
	 * @param	string	$name		Name
	 * @param	string	$value		Value
	 * @param	string	$hash		Hash
	 * @param	boolean	$overwrite	Boolean
	 * @return	string	Previous value
	 * @since	1.0
	 */
	function setVar($name, $value = null, $hash = 'method', $overwrite = true)
	{
		//If overwrite is true, makes sure the variable hasn't been set yet
		if(!$overwrite && array_key_exists($name, $_REQUEST)) 
		{
			return $_REQUEST[$name];
		}

		// Clean global request var
		$GLOBALS['_BREQUEST'][$name] = array();

		// Get the request hash value
		$hash = strtoupper($hash);
		if ($hash === 'METHOD') 
		{
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}

		$previous	= array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;

		switch ($hash)
		{
			case 'GET' :
				$_GET[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'POST' :
				$_POST[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'COOKIE' :
				$_COOKIE[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'FILES' :
				$_FILES[$name] = $value;
				break;
			case 'ENV'    :
				$_ENV['name'] = $value;
				break;
			case 'SERVER'    :
				$_SERVER['name'] = $value;
				break;
		}

		// Mark this variable as 'SET'
		$GLOBALS['_BREQUEST'][$name]['SET.'.$hash] = true;
		$GLOBALS['_BREQUEST'][$name]['SET.REQUEST'] = true;

		return $previous;
	}

	/**
	 * Fetches and returns a request array.
	 *
	 * The default behaviour is fetching variables depending on the
	 * current request method: GET and HEAD will result in returning
	 * $_GET, POST and PUT will result in returning $_POST.
	 *
	 * You can force the source by setting the $hash parameter:
	 *
	 *   post		$_POST
	 *   get		$_GET
	 *   files		$_FILES
	 *   cookie		$_COOKIE
	 *   env		$_ENV
	 *   server		$_SERVER
	 *   method		via current $_SERVER['REQUEST_METHOD']
	 *   default	$_REQUEST
	 *
	 * @static
	 * @param	string	$hash	to get (POST, GET, FILES, METHOD)
	 * @param	int		$mask	Filter mask for the variable
	 * @return	mixed	Request hash
	 * @since	1.0
	 */
	function get($hash = 'default', $mask = 0)
	{
		$hash = strtoupper($hash);

		if ($hash === 'METHOD')
		{
			$hash = strtoupper( $_SERVER['REQUEST_METHOD'] );
		}

		switch ($hash)
		{
			case 'GET' :
				$input = $_GET;
				break;

			case 'POST' :
				$input = $_POST;
				break;

			case 'FILES' :
				$input = $_FILES;
				break;

			case 'COOKIE' :
				$input = $_COOKIE;
				break;

			case 'ENV'    :
				$input = &$_ENV;
				break;

			case 'SERVER'    :
				$input = &$_SERVER;
				break;

			default:
				$input = $_REQUEST;
				break;
		}

		$result = BRequest::_cleanVar($input, $mask);

		// Handle magic quotes compatability
		if (get_magic_quotes_gpc() && ($hash != 'FILES'))
		{
			$result = BRequest::_stripSlashesRecursive( $result );
		}

		return $result;
	}

	/**
	 * Sets a request variable
	 *
	 * @param	array	An associative array of key-value pairs
	 * @param	string	The request variable to set (POST, GET, FILES, METHOD)
	 * @param	boolean	If true and an existing key is found, the value is overwritten, otherwise it is ingored
	 */
	function set( $array, $hash = 'default', $overwrite = true )
	{
		foreach ($array as $key => $value) 
		{
			BRequest::setVar($key, $value, $hash, $overwrite);
		}
	}

	/**
	 * Cleans the request from script injection.
	 *
	 * @static
	 * @return	void
	 * @since	1.0
	 */
	function clean()
	{
		BRequest::_cleanArray( $_FILES );
		BRequest::_cleanArray( $_ENV );
		BRequest::_cleanArray( $_GET );
		BRequest::_cleanArray( $_POST );
		BRequest::_cleanArray( $_COOKIE );
		BRequest::_cleanArray( $_SERVER );

		if (isset( $_SESSION ))
		{
			BRequest::_cleanArray( $_SESSION );
		}

		$REQUEST	= $_REQUEST;
		$GET		= $_GET;
		$POST		= $_POST;
		$COOKIE		= $_COOKIE;
		$FILES		= $_FILES;
		$ENV		= $_ENV;
		$SERVER		= $_SERVER;

		if (isset ( $_SESSION ))
		{
			$SESSION = $_SESSION;
		}

		foreach ($GLOBALS as $key => $value)
		{
			if ( $key != 'GLOBALS' )
			{
				unset ( $GLOBALS [ $key ] );
			}
		}
		$_REQUEST	= $REQUEST;
		$_GET		= $GET;
		$_POST		= $POST;
		$_COOKIE	= $COOKIE;
		$_FILES		= $FILES;
		$_ENV 		= $ENV;
		$_SERVER 	= $SERVER;

		if (isset ( $SESSION ))
		{
			$_SESSION = $SESSION;
		}

		// Make sure the request hash is clean on file inclusion
		$GLOBALS['_BREQUEST'] = array();
	}

	/**
	 * Adds an array to the GLOBALS array and checks that the GLOBALS variable is not being attacked
	 *
	 * @access	protected
	 * @param	array	$array	Array to clean
	 * @param	boolean	True if the array is to be added to the GLOBALS
	 * @since	1.0
	 */
	function _cleanArray( &$array, $globalise=false )
	{
		static $banned = array( '_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals' );

		foreach ($array as $key => $value)
		{
			// PHP GLOBALS injection bug
			$failed = in_array( strtolower( $key ), $banned );

			// PHP Zend_Hash_Del_Key_Or_Index bug
			$failed |= is_numeric( $key );
			if ($failed)
			{
				exit( 'Illegal variable <b>' . implode( '</b> or <b>', $banned ) . '</b> passed to script.' );
			}
			if ($globalise)
			{
				$GLOBALS[$key] = $value;
			}
		}
	}

	/**
	 * Clean up an input variable.
	 *
	 * @param mixed The input variable.
	 * @param int Filter bit mask. 1=no trim: If this flag is cleared and the
	 * input is a string, the string will have leading and trailing whitespace
	 * trimmed. 2=allow_raw: If set, no more filtering is performed, higher bits
	 * are ignored. 4=allow_html: HTML is allowed, but passed through a safe
	 * HTML filter first. If set, no more filtering is performed. If no bits
	 * other than the 1 bit is set, a strict filter is applied.
	 * @param string The variable type {@see BFilterInput::clean()}.
	 */
	function _cleanVar($var, $mask = 0, $type=null)
	{
		// Static input filters for specific settings
		static $noHtmlFilter	= null;
		static $safeHtmlFilter	= null;

		// If the no trim flag is not set, trim the variable
		if (!($mask & 1) && is_string($var))
		{
			$var = trim($var);
		}

		// Now we handle input filtering
		if ($mask & 2)
		{
			// If the allow raw flag is set, do not modify the variable
			$var = $var;
		}
		elseif ($mask & 4)
		{
			// If the allow html flag is set, apply a safe html filter to the variable
			if (is_null($safeHtmlFilter)) {
				$safeHtmlFilter = & BFilterInput::getInstance(null, null, 1, 1);
			}
			$var = $safeHtmlFilter->clean($var, $type);
		}
		else
		{
			// Since no allow flags were set, we will apply the most strict filter to the variable
			if (is_null($noHtmlFilter)) {
				$noHtmlFilter = & BFilterInput::getInstance(/* $tags, $attr, $tag_method, $attr_method, $xss_auto */);
			}
			$var = $noHtmlFilter->clean($var, $type);
			
		}
		return $var;
	}

	/**
	 * Strips slashes recursively on an array
	 *
	 * @access	protected
	 * @param	array	$array		Array of (nested arrays of) strings
	 * @return	array	The input array with stripshlashes applied to it
	 */
	function _stripSlashesRecursive( $value )
	{
		$value = is_array( $value ) ? array_map( array( 'BRequest', '_stripSlashesRecursive' ), $value ) : stripslashes( $value );
		return $value;
	}
	
}

/**
 * BFilterInput is a class for filtering input from any data source
 *
 * Forked from the php input filter library by: Daniel Morris <dan@rootcube.com>
 * Original Contributors: Gianpaolo Racca, Ghislain Picard, Marco Wandschneider, Chris Tobin and Andrew Eddie.
 *
 * @package 	Joomla.Framework
 * @subpackage		Filter
 * @since		1.5
 */
class BFilterInput
{
	var $tagsArray; // default = empty array
	var $attrArray; // default = empty array

	var $tagsMethod; // default = 0
	var $attrMethod; // default = 0

	var $xssAuto; // default = 1
	var $tagBlacklist = array ('applet', 'body', 'bgsound', 'base', 'basefont', 'embed', 'frame', 'frameset', 'head', 'html', 'id', 'iframe', 'ilayer', 'layer', 'link', 'meta', 'name', 'object', 'script', 'style', 'title', 'xml');
	var $attrBlacklist = array ('action', 'background', 'codebase', 'dynsrc', 'lowsrc'); // also will strip ALL event handlers

	/**
	 * Constructor for inputFilter class. Only first parameter is required.
	 *
	 * @access	protected
	 * @param	array	$tagsArray	list of user-defined tags
	 * @param	array	$attrArray	list of user-defined attributes
	 * @param	int		$tagsMethod	WhiteList method = 0, BlackList method = 1
	 * @param	int		$attrMethod	WhiteList method = 0, BlackList method = 1
	 * @param	int		$xssAuto	Only auto clean essentials = 0, Allow clean blacklisted tags/attr = 1
	 * @since	1.5
	 */
	function __construct($tagsArray = array(), $attrArray = array(), $tagsMethod = 0, $attrMethod = 0, $xssAuto = 1)
	{
		// Make sure user defined arrays are in lowercase
		$tagsArray = array_map('strtolower', (array) $tagsArray);
		$attrArray = array_map('strtolower', (array) $attrArray);

		// Assign member variables
		$this->tagsArray	= $tagsArray;
		$this->attrArray	= $attrArray;
		$this->tagsMethod	= $tagsMethod;
		$this->attrMethod	= $attrMethod;
		$this->xssAuto		= $xssAuto;
	}

	/**
	 * Returns a reference to an input filter object, only creating it if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		<pre>  $filter = & BFilterInput::getInstance();</pre>
	 *
	 * @static
	 * @param	array	$tagsArray	list of user-defined tags
	 * @param	array	$attrArray	list of user-defined attributes
	 * @param	int		$tagsMethod	WhiteList method = 0, BlackList method = 1
	 * @param	int		$attrMethod	WhiteList method = 0, BlackList method = 1
	 * @param	int		$xssAuto	Only auto clean essentials = 0, Allow clean blacklisted tags/attr = 1
	 * @return	object	The BFilterInput object.
	 * @since	1.5
	 */
	function & getInstance($tagsArray = array(), $attrArray = array(), $tagsMethod = 0, $attrMethod = 0, $xssAuto = 1)
	{
		static $instances;

		$sig = md5(serialize(array($tagsArray,$attrArray,$tagsMethod,$attrMethod,$xssAuto)));

		if (!isset ($instances)) {
			$instances = array();
		}

		if (empty ($instances[$sig])) {
			$instances[$sig] = new BFilterInput($tagsArray, $attrArray, $tagsMethod, $attrMethod, $xssAuto);
		}

		return $instances[$sig];
	}

	/**
	 * Method to be called by another php script. Processes for XSS and
	 * specified bad code.
	 *
	 * @access	public
	 * @param	mixed	$source	Input string/array-of-string to be 'cleaned'
	 * @param	string	$type	Return type for the variable (INT, FLOAT, BOOLEAN, WORD, ALNUM, CMD, BASE64, STRING, ARRAY, PATH, NONE)
	 * @return	mixed	'Cleaned' version of input parameter
	 * @since	1.5
	 * @static
	 */
	function clean($source, $type='string')
	{
		// Handle the type constraint
		switch (strtoupper($type))
		{
			case 'INT' :
			case 'INTEGER' :
				// Only use the first integer value
				preg_match('/-?[0-9]+/', (string) $source, $matches);
				$result = @ (int) $matches[0];
				break;

			case 'FLOAT' :
			case 'DOUBLE' :
				// Only use the first floating point value
				preg_match('/-?[0-9]+(\.[0-9]+)?/', (string) $source, $matches);
				$result = @ (float) $matches[0];
				break;

			case 'BOOL' :
			case 'BOOLEAN' :
				$result = (bool) $source;
				break;

			case 'WORD' :
				$result = (string) preg_replace( '/[^A-Z_]/i', '', $source );
				break;

			case 'ALNUM' :
				$result = (string) preg_replace( '/[^A-Z0-9]/i', '', $source );
				break;

			case 'CMD' :
				$result = (string) preg_replace( '/[^A-Z0-9_\.-]/i', '', $source );
				$result = ltrim($result, '.');
				break;

			case 'BASE64' :
				$result = (string) preg_replace( '/[^A-Z0-9\/+=]/i', '', $source );
				break;

			case 'STRING' :
				// Check for static usage and assign $filter the proper variable
				if(isset($this) && is_a( $this, 'BFilterInput' )) {
					$filter =& $this;
				} else {
					$filter =& BFilterInput::getInstance();
				}
				$result = (string) $filter->_remove($filter->_decode((string) $source));
				break;

			case 'ARRAY' :
				$result = (array) $source;
				break;

			case 'PATH' :
				$pattern = '/^[A-Za-z0-9_-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$/';
				preg_match($pattern, (string) $source, $matches);
				$result = @ (string) $matches[0];
				break;

			case 'USERNAME' :
				$result = (string) preg_replace( '/[\x00-\x1F\x7F<>"\'%&]/', '', $source );
				break;

			default :
				// Check for static usage and assign $filter the proper variable
				if(is_object($this) && get_class($this) == 'BFilterInput') {
					$filter =& $this;
				} else {
					$filter =& BFilterInput::getInstance();
				}
				// Are we dealing with an array?
				if (is_array($source)) {
					foreach ($source as $key => $value)
					{
						// filter element for XSS and other 'bad' code etc.
						if (is_string($value)) {
							$source[$key] = $filter->_remove($filter->_decode($value));
						}
					}
					$result = $source;
				} else {
					// Or a string?
					if (is_string($source) && !empty ($source)) {
						// filter source for XSS and other 'bad' code etc.
						$result = $filter->_remove($filter->_decode($source));
					} else {
						// Not an array or string.. return the passed parameter
						$result = $source;
					}
				}
				break;
		}
		return $result;
	}

	/**
	 * Function to determine if contents of an attribute is safe
	 *
	 * @static
	 * @param	array	$attrSubSet	A 2 element array for attributes name,value
	 * @return	boolean True if bad code is detected
	 * @since	1.5
	 */
	function checkAttribute($attrSubSet)
	{
		$attrSubSet[0] = strtolower($attrSubSet[0]);
		$attrSubSet[1] = strtolower($attrSubSet[1]);
		return (((strpos($attrSubSet[1], 'expression') !== false) && ($attrSubSet[0]) == 'style') || (strpos($attrSubSet[1], 'javascript:') !== false) || (strpos($attrSubSet[1], 'behaviour:') !== false) || (strpos($attrSubSet[1], 'vbscript:') !== false) || (strpos($attrSubSet[1], 'mocha:') !== false) || (strpos($attrSubSet[1], 'livescript:') !== false));
	}

	/**
	 * Internal method to iteratively remove all unwanted tags and attributes
	 *
	 * @access	protected
	 * @param	string	$source	Input string to be 'cleaned'
	 * @return	string	'Cleaned' version of input parameter
	 * @since	1.5
	 */
	function _remove($source)
	{
		$loopCounter = 0;

		// Iteration provides nested tag protection
		while ($source != $this->_cleanTags($source))
		{
			$source = $this->_cleanTags($source);
			$loopCounter ++;
		}
		return $source;
	}

	/**
	 * Internal method to strip a string of certain tags
	 *
	 * @access	protected
	 * @param	string	$source	Input string to be 'cleaned'
	 * @return	string	'Cleaned' version of input parameter
	 * @since	1.5
	 */
	function _cleanTags($source)
	{
		/*
		 * In the beginning we don't really have a tag, so everything is
		 * postTag
		 */
		$preTag		= null;
		$postTag	= $source;
		$currentSpace = false;
		$attr = '';	 // moffats: setting to null due to issues in migration system - undefined variable errors

		// Is there a tag? If so it will certainly start with a '<'
		$tagOpen_start	= strpos($source, '<');

		while ($tagOpen_start !== false)
		{
			// Get some information about the tag we are processing
			$preTag			.= substr($postTag, 0, $tagOpen_start);
			$postTag		= substr($postTag, $tagOpen_start);
			$fromTagOpen	= substr($postTag, 1);
			$tagOpen_end	= strpos($fromTagOpen, '>');

			// Let's catch any non-terminated tags and skip over them
			if ($tagOpen_end === false) {
				$postTag		= substr($postTag, $tagOpen_start +1);
				$tagOpen_start	= strpos($postTag, '<');
				continue;
			}

			// Do we have a nested tag?
			$tagOpen_nested = strpos($fromTagOpen, '<');
			$tagOpen_nested_end	= strpos(substr($postTag, $tagOpen_end), '>');
			if (($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end)) {
				$preTag			.= substr($postTag, 0, ($tagOpen_nested +1));
				$postTag		= substr($postTag, ($tagOpen_nested +1));
				$tagOpen_start	= strpos($postTag, '<');
				continue;
			}

			// Lets get some information about our tag and setup attribute pairs
			$tagOpen_nested	= (strpos($fromTagOpen, '<') + $tagOpen_start +1);
			$currentTag		= substr($fromTagOpen, 0, $tagOpen_end);
			$tagLength		= strlen($currentTag);
			$tagLeft		= $currentTag;
			$attrSet		= array ();
			$currentSpace	= strpos($tagLeft, ' ');

			// Are we an open tag or a close tag?
			if (substr($currentTag, 0, 1) == '/') {
				// Close Tag
				$isCloseTag		= true;
				list ($tagName)	= explode(' ', $currentTag);
				$tagName		= substr($tagName, 1);
			} else {
				// Open Tag
				$isCloseTag		= false;
				list ($tagName)	= explode(' ', $currentTag);
			}

			/*
			 * Exclude all "non-regular" tagnames
			 * OR no tagname
			 * OR remove if xssauto is on and tag is blacklisted
			 */
			if ((!preg_match("/^[a-z][a-z0-9]*$/i", $tagName)) || (!$tagName) || ((in_array(strtolower($tagName), $this->tagBlacklist)) && ($this->xssAuto))) {
				$postTag		= substr($postTag, ($tagLength +2));
				$tagOpen_start	= strpos($postTag, '<');
				// Strip tag
				continue;
			}

			/*
			 * Time to grab any attributes from the tag... need this section in
			 * case attributes have spaces in the values.
			 */
			while ($currentSpace !== false)
			{
				$attr			= '';
				$fromSpace		= substr($tagLeft, ($currentSpace +1));
				$nextSpace		= strpos($fromSpace, ' ');
				$openQuotes		= strpos($fromSpace, '"');
				$closeQuotes	= strpos(substr($fromSpace, ($openQuotes +1)), '"') + $openQuotes +1;

				// Do we have an attribute to process? [check for equal sign]
				if (strpos($fromSpace, '=') !== false) {
					/*
					 * If the attribute value is wrapped in quotes we need to
					 * grab the substring from the closing quote, otherwise grab
					 * till the next space
					 */
					if (($openQuotes !== false) && (strpos(substr($fromSpace, ($openQuotes +1)), '"') !== false)) {
						$attr = substr($fromSpace, 0, ($closeQuotes +1));
					} else {
						$attr = substr($fromSpace, 0, $nextSpace);
					}
				} else {
					/*
					 * No more equal signs so add any extra text in the tag into
					 * the attribute array [eg. checked]
					 */
					if ($fromSpace != '/') {
						$attr = substr($fromSpace, 0, $nextSpace);
					}
				}

				// Last Attribute Pair
				if (!$attr && $fromSpace != '/') {
					$attr = $fromSpace;
				}

				// Add attribute pair to the attribute array
				$attrSet[] = $attr;

				// Move search point and continue iteration
				$tagLeft		= substr($fromSpace, strlen($attr));
				$currentSpace	= strpos($tagLeft, ' ');
			}

			// Is our tag in the user input array?
			$tagFound = in_array(strtolower($tagName), $this->tagsArray);

			// If the tag is allowed lets append it to the output string
			if ((!$tagFound && $this->tagsMethod) || ($tagFound && !$this->tagsMethod)) {

				// Reconstruct tag with allowed attributes
				if (!$isCloseTag) {
					// Open or Single tag
					$attrSet = $this->_cleanAttributes($attrSet);
					$preTag .= '<'.$tagName;
					for ($i = 0; $i < count($attrSet); $i ++)
					{
						$preTag .= ' '.$attrSet[$i];
					}

					// Reformat single tags to XHTML
					if (strpos($fromTagOpen, '</'.$tagName)) {
						$preTag .= '>';
					} else {
						$preTag .= ' />';
					}
				} else {
					// Closing Tag
					$preTag .= '</'.$tagName.'>';
				}
			}

			// Find next tag's start and continue iteration
			$postTag		= substr($postTag, ($tagLength +2));
			$tagOpen_start	= strpos($postTag, '<');
		}

		// Append any code after the end of tags and return
		if ($postTag != '<') {
			$preTag .= $postTag;
		}
		return $preTag;
	}

	/**
	 * Internal method to strip a tag of certain attributes
	 *
	 * @access	protected
	 * @param	array	$attrSet	Array of attribute pairs to filter
	 * @return	array	Filtered array of attribute pairs
	 * @since	1.5
	 */
	function _cleanAttributes($attrSet)
	{
		// Initialize variables
		$newSet = array();

		// Iterate through attribute pairs
		for ($i = 0; $i < count($attrSet); $i ++)
		{
			// Skip blank spaces
			if (!$attrSet[$i]) {
				continue;
			}

			// Split into name/value pairs
			$attrSubSet = explode('=', trim($attrSet[$i]), 2);
			list ($attrSubSet[0]) = explode(' ', $attrSubSet[0]);

			/*
			 * Remove all "non-regular" attribute names
			 * AND blacklisted attributes
			 */
			if ((!preg_match('/[a-z]*$/i', $attrSubSet[0])) || (($this->xssAuto) && ((in_array(strtolower($attrSubSet[0]), $this->attrBlacklist)) || (substr($attrSubSet[0], 0, 2) == 'on')))) {
				continue;
			}

			// XSS attribute value filtering
			if ($attrSubSet[1]) {
				// strips unicode, hex, etc
				$attrSubSet[1] = str_replace('&#', '', $attrSubSet[1]);
				// strip normal newline within attr value
				$attrSubSet[1] = preg_replace('/[\n\r]/', '', $attrSubSet[1]);
				// strip double quotes
				$attrSubSet[1] = str_replace('"', '', $attrSubSet[1]);
				// convert single quotes from either side to doubles (Single quotes shouldn't be used to pad attr value)
				if ((substr($attrSubSet[1], 0, 1) == "'") && (substr($attrSubSet[1], (strlen($attrSubSet[1]) - 1), 1) == "'")) {
					$attrSubSet[1] = substr($attrSubSet[1], 1, (strlen($attrSubSet[1]) - 2));
				}
				// strip slashes
				$attrSubSet[1] = stripslashes($attrSubSet[1]);
			}

			// Autostrip script tags
			if (BFilterInput::checkAttribute($attrSubSet)) {
				continue;
			}

			// Is our attribute in the user input array?
			$attrFound = in_array(strtolower($attrSubSet[0]), $this->attrArray);

			// If the tag is allowed lets keep it
			if ((!$attrFound && $this->attrMethod) || ($attrFound && !$this->attrMethod)) {

				// Does the attribute have a value?
				if ($attrSubSet[1]) {
					$newSet[] = $attrSubSet[0].'="'.$attrSubSet[1].'"';
				} elseif ($attrSubSet[1] == "0") {
					/*
					 * Special Case
					 * Is the value 0?
					 */
					$newSet[] = $attrSubSet[0].'="0"';
				} else {
					$newSet[] = $attrSubSet[0].'="'.$attrSubSet[0].'"';
				}
			}
		}
		return $newSet;
	}

	/**
	 * Try to convert to plaintext
	 *
	 * @access	protected
	 * @param	string	$source
	 * @return	string	Plaintext string
	 * @since	1.5
	 */
	function _decode($source)
	{
		// entity decode
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		foreach($trans_tbl as $k => $v) {
			$ttr[$v] = utf8_encode($k);
		}
		$source = strtr($source, $ttr);
		// convert decimal
		$source = preg_replace('/&#(\d+);/me', "utf8_encode(chr(\\1))", $source); // decimal notation
		// convert hex
		$source = preg_replace('/&#x([a-f0-9]+);/mei', "utf8_encode(chr(0x\\1))", $source); // hex notation
		return $source;
	}
}

/**
 * Retrieve the raw response from the HTTP request using the GET method.
 *
 * @see wp_remote_request() For more information on the response array format.
 *
 * @since 2.7.0
 *
 * @param string $url Site URL to retrieve.
 * @param array $args Optional. Override the defaults.
 * @return WP_Error|array The response or WP_Error on failure.
 */
function five_remote_get($url, $args = array())
{
	//initializing
	$defaults = array(
		'method' => 'POST',
		'timeout' => apply_filters( 'http_request_timeout', 5),
		'redirection' => apply_filters( 'http_request_redirection_count', 5),
		'httpversion' => apply_filters( 'http_request_version', '1.0'),
		'user-agent' => apply_filters( 'http_headers_useragent', '5TwentyStudiosCMS/1.0; http://www.5twentystudios.com' ),
		'blocking' => true,
		'headers' => array(),
		'cookies' => array(),
		'body' => null,
		'compress' => false,
		'decompress' => true,
		'sslverify' => true
	);
	
	$r = five_parse_args( $args, $defaults );
	$r = apply_filters( 'http_request_args', $r, $url );
	
	// Allow plugins to short-circuit the request
	$pre = apply_filters( 'pre_http_request', false, $r, $url );
	if ( false !== $pre )
		return $pre;
	
	$fiveConfig = fiveConfigurations();
	$arrURL = parse_url( $url );
	
	if ( empty( $url ) || empty( $arrURL['scheme'] ) )
		return array('error' => 'http_request_insufficient_data', '' => 'A valid URL was not provided.');
	
	// Determine if this is a https call and pass that on to the transport functions
	// so that we can blacklist the transports that do not support ssl verification
	$r['ssl'] = $arrURL['scheme'] == 'https' || $arrURL['scheme'] == 'ssl';
	
	// Determine if this request is to OUR install of WordPress
	$homeURL = parse_url( $fiveConfig->base->url );
	$r['local'] = $homeURL['host'] == $arrURL['host'] || 'localhost' == $arrURL['host'];
	unset( $homeURL );
	
	if ( is_null( $r['headers'] ) )
		$r['headers'] = array();
	
	if ( ! is_array( $r['headers'] ) ) {
		$processedHeaders = BCurl::processHeaders( $r['headers'] );
		$r['headers'] = $processedHeaders['headers'];
	}
		
	if ( isset( $r['headers']['User-Agent'] ) ) {
		$r['user-agent'] = $r['headers']['User-Agent'];
		unset( $r['headers']['User-Agent'] );
	}

	if ( isset( $r['headers']['user-agent'] ) ) {
		$r['user-agent'] = $r['headers']['user-agent'];
		unset( $r['headers']['user-agent'] );
	}
	
	if ( BCurl::encoding_is_available() )
		$r['headers']['Accept-Encoding'] = BCurl::accept_encoding();
	
	if ( empty($r['body']) ) {
		$r['body'] = null;
		// Some servers fail when sending content without the content-length header being set.
		// Also, to fix another bug, we only send when doing POST and PUT and the content-length
		// header isn't already set.
		if ( ($r['method'] == 'POST' || $r['method'] == 'PUT') && ! isset( $r['headers']['Content-Length'] ) )
			$r['headers']['Content-Length'] = 0;

	} else {
		if ( is_array( $r['body'] ) || is_object( $r['body'] ) ) {
			if ( ! version_compare(phpversion(), '5.1.2', '>=') )
				$r['body'] = _http_build_query( $r['body'], null, '&' );
			else
				$r['body'] = http_build_query( $r['body'], null, '&' );
			$r['headers']['Content-Type'] = 'application/x-www-form-urlencoded; charset=' . get_option( 'blog_charset' );
			$r['headers']['Content-Length'] = strlen( $r['body'] );
		}

		if ( ! isset( $r['headers']['Content-Length'] ) && ! isset( $r['headers']['content-length'] ) )
			$r['headers']['Content-Length'] = strlen( $r['body'] );

	}
	
	//finally..  make the request
	$bcurl = new BCurl;
	$response = $bcurl->request( $url, $r );
	
	return $response;
}

/**
 * HTTP request method uses Curl extension to retrieve the url.
 *
 * Requires the Curl extension to be installed.
 *
 * @subpackage HTTP
 * @since 1.0
 */
class BCurl {

	/**
	 * Send a HTTP request to a URI using cURL extension.
	 *
	 * @access public
	 * @since 2.7.0
	 *
	 * @param string $url
	 * @param str|array $args Optional. Override the defaults.
	 * @return array 'headers', 'body', 'cookies' and 'response' keys.
	 */
	function request($url, $args = array()) {
		$defaults = array(
			'method' => 'POST', 'timeout' => 5,
			'redirection' => 5, 'httpversion' => '1.0',
			'blocking' => true, 'sslverify' => true,
			'headers' => array(), 'body' => null, 'cookies' => array()
		);

		$r = five_parse_args( $args, $defaults );

		if ( isset($r['headers']['User-Agent']) ) {
			$r['user-agent'] = $r['headers']['User-Agent'];
			unset($r['headers']['User-Agent']);
		} else if ( isset($r['headers']['user-agent']) ) {
			$r['user-agent'] = $r['headers']['user-agent'];
			unset($r['headers']['user-agent']);
		}
		
		$handle = curl_init();
		
		$is_local = isset($args['local']) && $args['local'];
		$ssl_verify = isset($args['sslverify']) && $args['sslverify'];
		if ( $is_local )
			$ssl_verify = apply_filters('https_local_ssl_verify', $ssl_verify);
		elseif ( ! $is_local )
			$ssl_verify = apply_filters('https_ssl_verify', $ssl_verify);


		// CURLOPT_TIMEOUT and CURLOPT_CONNECTTIMEOUT expect integers.  Have to use ceil since
		// a value of 0 will allow an unlimited timeout.
		$timeout = (int) ceil( $r['timeout'] );
		curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt( $handle, CURLOPT_TIMEOUT, $timeout );

		curl_setopt( $handle, CURLOPT_URL, $url);
		curl_setopt( $handle, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $handle, CURLOPT_SSL_VERIFYHOST, $ssl_verify );
		curl_setopt( $handle, CURLOPT_SSL_VERIFYPEER, $ssl_verify );
		curl_setopt( $handle, CURLOPT_USERAGENT, $r['user-agent'] );
		curl_setopt( $handle, CURLOPT_MAXREDIRS, $r['redirection'] );

		switch ( $r['method'] ) {
			case 'HEAD':
				curl_setopt( $handle, CURLOPT_NOBODY, true );
				break;
			case 'POST':
				curl_setopt( $handle, CURLOPT_POST, true );
				curl_setopt( $handle, CURLOPT_POSTFIELDS, $r['body'] );
				break;
			case 'PUT':
				curl_setopt( $handle, CURLOPT_CUSTOMREQUEST, 'PUT' );
				curl_setopt( $handle, CURLOPT_POSTFIELDS, $r['body'] );
				break;
		}

		if ( true === $r['blocking'] )
			curl_setopt( $handle, CURLOPT_HEADER, true );
		else
			curl_setopt( $handle, CURLOPT_HEADER, false );

		// The option doesn't work with safe mode or when open_basedir is set.
		// Disable HEAD when making HEAD requests.
		if ( !ini_get('safe_mode') && !ini_get('open_basedir') && 'HEAD' != $r['method'] )
			curl_setopt( $handle, CURLOPT_FOLLOWLOCATION, true );

		if ( !empty( $r['headers'] ) ) {
			// cURL expects full header strings in each element
			$headers = array();
			foreach ( $r['headers'] as $name => $value ) {
				$headers[] = "{$name}: $value";
			}
			curl_setopt( $handle, CURLOPT_HTTPHEADER, $headers );
		}

		if ( $r['httpversion'] == '1.0' )
			curl_setopt( $handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
		else
			curl_setopt( $handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );

		// Cookies are not handled by the HTTP API currently. Allow for plugin authors to handle it
		// themselves... Although, it is somewhat pointless without some reference.
		do_action_ref_array( 'http_api_curl', array(&$handle) );

		// We don't need to return the body, so don't. Just execute request and return.
		if ( ! $r['blocking'] ) {
			curl_exec( $handle );
			curl_close( $handle );
			return array( 'headers' => array(), 'body' => '', 'response' => array('code' => false, 'message' => false), 'cookies' => array() );
		}

		$theResponse = curl_exec( $handle );

		if ( !empty($theResponse) ) {
			$headerLength = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
			$theHeaders = trim( substr($theResponse, 0, $headerLength) );
			if ( strlen($theResponse) > $headerLength )
				$theBody = substr( $theResponse, $headerLength );
			else
				$theBody = '';
			if ( false !== strpos($theHeaders, "\r\n\r\n") ) {
				$headerParts = explode("\r\n\r\n", $theHeaders);
				$theHeaders = $headerParts[ count($headerParts) -1 ];
			}
			$theHeaders = BCurl::processHeaders($theHeaders);
		} else {
			if ( $curl_error = curl_error($handle) )
				return array( 'error' => 'http_request_failed', 'errorMsg' => $curl_error);
			if ( in_array( curl_getinfo( $handle, CURLINFO_HTTP_CODE ), array(301, 302) ) )
				return array( 'error' => 'http_request_failed', 'errorMsg' => 'Too many redirects.');

			$theHeaders = array( 'headers' => array(), 'cookies' => array() );
			$theBody = '';
		}

		$response = array();
		$response['code'] = curl_getinfo( $handle, CURLINFO_HTTP_CODE );
		$response['message'] = get_status_header_desc($response['code']);

		curl_close( $handle );

		// See #11305 - When running under safe mode, redirection is disabled above. Handle it manually.
		if ( !empty($theHeaders['headers']['location']) && (ini_get('safe_mode') || ini_get('open_basedir')) ) {
			if ( $r['redirection']-- > 0 ) {
				return $this->request($theHeaders['headers']['location'], $r);
			} else {
				return array( 'error' => 'http_request_failed', 'errorMsg' => 'Too many redirects.');
			}
		}

		if ( true === $r['decompress'] && true === BCurl::should_decode($theHeaders['headers']) )
			$theBody = BCurl::decompress( $theBody );

		return array('headers' => $theHeaders['headers'], 'body' => $theBody, 'response' => $response, 'cookies' => $theHeaders['cookies']);
	}

	/**
	 * Whether decompression and compression are supported by the PHP version.
	 *
	 * Each function is tested instead of checking for the zlib extension, to
	 * ensure that the functions all exist in the PHP version and aren't
	 * disabled.
	 *
	 * @since 2.8
	 *
	 * @return bool
	 */
	function encoding_is_available() {
		return ( function_exists('gzuncompress') || function_exists('gzdeflate') || function_exists('gzinflate') );
	}

	/**
	 * What encoding types to accept and their priority values.
	 *
	 * @since 2.8
	 *
	 * @return string Types of encoding to accept.
	 */
	function accept_encoding() {
		$type = array();
		if ( function_exists( 'gzinflate' ) )
			$type[] = 'deflate;q=1.0';

		if ( function_exists( 'gzuncompress' ) )
			$type[] = 'compress;q=0.5';

		if ( function_exists( 'gzdecode' ) )
			$type[] = 'gzip;q=0.5';

		return implode(', ', $type);
	}

	/**
	 * Whether the content be decoded based on the headers.
	 *
	 * @since 2.8
	 *
	 * @param array|string $headers All of the available headers.
	 * @return bool
	 */
	function should_decode($headers) {
		if ( is_array( $headers ) ) {
			if ( array_key_exists('content-encoding', $headers) && ! empty( $headers['content-encoding'] ) )
				return true;
		} else if ( is_string( $headers ) ) {
			return ( stripos($headers, 'content-encoding:') !== false );
		}

		return false;
	}

	/**
	 * Decompression of deflated string.
	 *
	 * Will attempt to decompress using the RFC 1950 standard, and if that fails
	 * then the RFC 1951 standard deflate will be attempted. Finally, the RFC
	 * 1952 standard gzip decode will be attempted. If all fail, then the
	 * original compressed string will be returned.
	 *
	 * @since 2.8
	 *
	 * @param string $compressed String to decompress.
	 * @param int $length The optional length of the compressed data.
	 * @return string|bool False on failure.
	 */
	function decompress( $compressed, $length = null ) {

		if ( empty($compressed) )
			return $compressed;

		if ( false !== ( $decompressed = @gzinflate( $compressed ) ) )
			return $decompressed;

		if ( false !== ( $decompressed = BCurl::compatible_gzinflate( $compressed ) ) )
			return $decompressed;

		if ( false !== ( $decompressed = @gzuncompress( $compressed ) ) )
			return $decompressed;

		if ( function_exists('gzdecode') ) {
			$decompressed = @gzdecode( $compressed );

			if ( false !== $decompressed )
				return $decompressed;
		}

		return $compressed;
	}

	/**
	 * Decompression of deflated string while staying compatible with the majority of servers.
	 *
	 * Certain Servers will return deflated data with headers which PHP's gziniflate()
	 * function cannot handle out of the box. The following function lifted from
	 * http://au2.php.net/manual/en/function.gzinflate.php#77336 will attempt to deflate
	 * the various return forms used.
	 *
	 * @since 2.8.1
	 * @link http://au2.php.net/manual/en/function.gzinflate.php#77336
	 *
	 * @param string $gzData String to decompress.
	 * @return string|bool False on failure.
	 */
	function compatible_gzinflate($gzData) {
		if ( substr($gzData, 0, 3) == "\x1f\x8b\x08" ) {
			$i = 10;
			$flg = ord( substr($gzData, 3, 1) );
			if ( $flg > 0 ) {
				if ( $flg & 4 ) {
					list($xlen) = unpack('v', substr($gzData, $i, 2) );
					$i = $i + 2 + $xlen;
				}
				if ( $flg & 8 )
					$i = strpos($gzData, "\0", $i) + 1;
				if ( $flg & 16 )
					$i = strpos($gzData, "\0", $i) + 1;
				if ( $flg & 2 )
					$i = $i + 2;
			}
			return gzinflate( substr($gzData, $i, -8) );
		} else {
			return false;
		}
	}

	/**
	 * Transform header string into an array.
	 *
	 * If an array is given then it is assumed to be raw header data with numeric keys with the
	 * headers as the values. No headers must be passed that were already processed.
	 *
	 * @access public
	 * @static
	 * @since 2.7.0
	 *
	 * @param string|array $headers
	 * @return array Processed string headers. If duplicate headers are encountered,
	 * 					Then a numbered array is returned as the value of that header-key.
	 */
	function processHeaders($headers) {
		// split headers, one per array element
		if ( is_string($headers) ) {
			// tolerate line terminator: CRLF = LF (RFC 2616 19.3)
			$headers = str_replace("\r\n", "\n", $headers);
			// unfold folded header fields. LWS = [CRLF] 1*( SP | HT ) <US-ASCII SP, space (32)>, <US-ASCII HT, horizontal-tab (9)> (RFC 2616 2.2)
			$headers = preg_replace('/\n[ \t]/', ' ', $headers);
			// create the headers array
			$headers = explode("\n", $headers);
		}

		$response = array('code' => 0, 'message' => '');

		// If a redirection has taken place, The headers for each page request may have been passed.
		// In this case, determine the final HTTP header and parse from there.
		for ( $i = count($headers)-1; $i >= 0; $i-- ) {
			if ( !empty($headers[$i]) && false === strpos($headers[$i], ':') ) {
				$headers = array_splice($headers, $i);
				break;
			}
		}

		$cookies = array();
		$newheaders = array();
		foreach ( $headers as $tempheader ) {
			if ( empty($tempheader) )
				continue;

			if ( false === strpos($tempheader, ':') ) {
				list( , $response['code'], $response['message']) = explode(' ', $tempheader, 3);
				continue;
			}

			list($key, $value) = explode(':', $tempheader, 2);

			if ( !empty( $value ) ) {
				$key = strtolower( $key );
				if ( isset( $newheaders[$key] ) ) {
					if ( !is_array($newheaders[$key]) )
						$newheaders[$key] = array($newheaders[$key]);
					$newheaders[$key][] = trim( $value );
				} else {
					$newheaders[$key] = trim( $value );
				}
			}
		}

		return array('response' => $response, 'headers' => $newheaders, 'cookies' => $cookies);
	}

	/**
	 * Whether this class can be used for retrieving an URL.
	 *
	 * @static
	 * @since 2.7.0
	 *
	 * @return boolean False means this class can not be used, true means it can.
	 */
	function test($args = array()) {
		if ( function_exists('curl_init') && function_exists('curl_exec') )
			return apply_filters('use_curl_transport', true, $args);

		return false;
	}
}