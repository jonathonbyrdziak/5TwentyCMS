<?php 
/**
 * @Author Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package PublicMarketSpace
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or _die("Cannot access pages directly.");

/**
 * Merge user defined arguments into defaults array.
 *
 * This function is used throughout WordPress to allow for both string or array
 * to be merged into another array.
 *
 * @since 2.2.0
 *
 * @param string|array $args Value to merge with $defaults
 * @param array $defaults Array that serves as the defaults.
 * @return array Merged user defined values with defaults.
 */
function five_parse_args( $args, $defaults = '' ) {
	if ( is_object( $args ) )
		$r = get_object_vars( $args );
	elseif ( is_array( $args ) )
		$r =& $args;
	else
		five_parse_str( $args, $r );

	if ( is_array( $defaults ) )
		return array_merge( $defaults, $r );
	return $r;
}

/**
 * Parses a string into variables to be stored in an array.
 *
 * Uses {@link http://www.php.net/parse_str parse_str()} and stripslashes if
 * {@link http://www.php.net/magic_quotes magic_quotes_gpc} is on.
 *
 * @since 2.2.1
 * @uses apply_filters() for the 'wp_parse_str' filter.
 *
 * @param string $string The string to be parsed.
 * @param array $array Variables will be stored in this array.
 */
function five_parse_str( $string, &$array ) {
	parse_str( $string, $array );
	if ( get_magic_quotes_gpc() )
		$array = stripslashes_deep( $array );
	$array = apply_filters( 'wp_parse_str', $array );
}

/**
 * Combine user attributes with known attributes and fill in defaults when needed.
 *
 * The pairs should be considered to be all of the attributes which are
 * supported by the caller and given as a list. The returned attributes will
 * only contain the attributes in the $pairs list.
 *
 * If the $atts list has unsupported attributes, then they will be ignored and
 * removed from the final returned list.
 *
 * @since 2.5
 *
 * @param array $pairs Entire list of supported attributes and their defaults.
 * @param array $atts User defined attributes in shortcode tag.
 * @return array Combined and filtered attribute list.
 */
function five_shortcode_atts($pairs, $atts) {
	$atts = (array)$atts;
	$out = array();
	foreach($pairs as $name => $default) {
		if ( array_key_exists($name, $atts) )
			$out[$name] = $atts[$name];
		else
			$out[$name] = $default;
	}
	return $out;
}

/**
 * Navigates through an array and removes slashes from the values.
 *
 * If an array is passed, the array_map() function causes a callback to pass the
 * value back to the function. The slashes from this value will removed.
 *
 * @since 2.0.0
 *
 * @param array|string $value The array or string to be striped.
 * @return array|string Stripped array (or string in the callback).
 */
function stripslashes_deep($value) {
	if ( is_array($value) ) {
		$value = array_map('stripslashes_deep', $value);
	} elseif ( is_object($value) ) {
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) {
			$value->{$key} = stripslashes_deep( $data );
		}
	} else {
		$value = stripslashes($value);
	}

	return $value;
}

/**
 * Retrieve the description for the HTTP status.
 *
 * @since 2.3.0
 *
 * @param int $code HTTP status code.
 * @return string Empty string if not found, or description if found.
 */
function get_status_header_desc( $code ) {
	global $header_to_desc;

	$code = absint( $code );

	if ( !isset( $header_to_desc ) ) {
		$header_to_desc = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			102 => 'Processing',

			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			207 => 'Multi-Status',
			226 => 'IM Used',

			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => 'Reserved',
			307 => 'Temporary Redirect',

			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			422 => 'Unprocessable Entity',
			423 => 'Locked',
			424 => 'Failed Dependency',
			426 => 'Upgrade Required',

			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			506 => 'Variant Also Negotiates',
			507 => 'Insufficient Storage',
			510 => 'Not Extended'
		);
	}

	if ( isset( $header_to_desc[$code] ) )
		return $header_to_desc[$code];
	else
		return '';
}

/**
 * Converts value to nonnegative integer.
 *
 * @since 2.5.0
 *
 * @param mixed $maybeint Data you wish to have convered to an nonnegative integer
 * @return int An nonnegative integer
 */
function absint( $maybeint )
{
	return abs( intval( $maybeint ) );
}

/**
 * Verifies that an email is valid.
 *
 * Does not grok i18n domains. Not RFC compliant.
 *
 * @since 0.71
 *
 * @param string $email Email address to verify.
 * @return string|bool Either false or the valid email address.
 */
function is_email( $email )
{
	// Test for the minimum length the email can be
	if ( strlen( $email ) < 3 ) {
		return apply_filters( 'is_email', false, $email, 'email_too_short' );
	}
	
	// Test for an @ character after the first position
	if ( strpos( $email, '@', 1 ) === false ) {
		return apply_filters( 'is_email', false, $email, 'email_no_at' );
	}
	
	// Split out the local and domain parts
	list( $local, $domain ) = explode( '@', $email, 2 );
	
	// LOCAL PART
	// Test for invalid characters
	if ( !preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) {
		return apply_filters( 'is_email', false, $email, 'local_invalid_chars' );
	}
	
	// DOMAIN PART
	// Test for sequences of periods
	if ( preg_match( '/\.{2,}/', $domain ) ) {
		return apply_filters( 'is_email', false, $email, 'domain_period_sequence' );
	}
	
	// Test for leading and trailing periods and whitespace
	if ( trim( $domain, " \t\n\r\0\x0B." ) !== $domain ) {
		return apply_filters( 'is_email', false, $email, 'domain_period_limits' );
	}
	
	// Split the domain into subs
	$subs = explode( '.', $domain );
	
	// Assume the domain will have at least two subs
	if ( 2 > count( $subs ) ) {
		return apply_filters( 'is_email', false, $email, 'domain_no_periods' );
	}
	
	// Loop through each sub
	foreach ( $subs as $sub ) {
		// Test for leading and trailing hyphens and whitespace
		if ( trim( $sub, " \t\n\r\0\x0B-" ) !== $sub ) {
			return apply_filters( 'is_email', false, $email, 'sub_hyphen_limits' );
		}

		// Test for invalid characters
		if ( !preg_match('/^[a-z0-9-]+$/i', $sub ) ) {
			return apply_filters( 'is_email', false, $email, 'sub_invalid_chars' );
		}
	}
	
	// Congratulations your email made it!
	return apply_filters( 'is_email', $email, $email, null );
}

/**
 * Create Global Unique Identifier
 * 
 * Method will activate only if sugar has not already activated this
 * same method. This method has been copied from the sugar files and
 * is used for cakphp database saving methods.
 * 
 * There is no format to these unique ID's other then that they are
 * globally unique and based on a microtime value
 * 
 * @return string //aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 */
function create_guid()
{
	$microTime = microtime();
	list($a_dec, $a_sec) = explode(" ", $microTime);
	
	$dec_hex = sprintf("%x", $a_dec* 1000000);
	$sec_hex = sprintf("%x", $a_sec);
	
	ensure_length($dec_hex, 5);
	ensure_length($sec_hex, 6);
	
	$guid = "";
	$guid .= $dec_hex;
	$guid .= create_guid_section(3);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= create_guid_section(4);
	$guid .= '-';
	$guid .= $sec_hex;
	$guid .= create_guid_section(6);
	
	return $guid;
}
function create_guid_section($characters)
{
	$return = "";
	for($i=0; $i<$characters; $i++)
	{
		$return .= sprintf("%x", mt_rand(0,15));
	}
	return $return;
}
function ensure_length(&$string, $length)
{
	$strlen = strlen($string);
	if($strlen < $length)
	{
		$string = str_pad($string,$length,"0");
	}
	else if($strlen > $length)
	{
		$string = substr($string, 0, $length);
	}
}

/**
 * function is responsible for creating a slug from the given string
 * 
 * @param unknown_type $string
 * @return string
 */
function slug( $string )
{
	$slug = strtolower(str_replace(" ", "-", preg_replace("/[^a-zA-Z0-9\-]/", "", $string)));
	return $slug;
}