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
 * Function checks to see if theres a current user logged in
 *
 * @return unknown
 */
function is_user_logged_in()
{
	if(!isset($_SESSION['user']['id'])) return false;
	return true;
}


function get_current_user_id()
{
	if(isset($_SESSION['user']['id'])) return $_SESSION['user']['id'];
	return 0;
}

/**
 * Retrieve the avatar for a user who provided a user ID or email address.
 *
 * @since 2.5
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 * @param int $size Size of the avatar image
 * @param string $default URL to a default image to use if no avatar is available
 * @param string $alt Alternate text to use in image tag. Defaults to blank
 * @return string <img> tag for the user's avatar
*/
function get_avatar( $id_or_email, $size = '150', $default = '', $alt = false )
{
	if ( false === $alt)
		$safe_alt = '';
	else
		$safe_alt = esc_attr( $alt );

	if ( !is_numeric($size) )
		$size = '96';

	$email = '';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user =& FiveFactory::getUser($id);
		if ( $user )
			$email = $user->email;
			
	} elseif ( is_object($id_or_email) ) {
		if ( !empty($id_or_email->id) ) {
			$user = $id_or_email;
			$id = (int) $id_or_email->id;
			if ( $user )
				$email = $user->email;
		}
		
	} else {
		$email = $id_or_email;
	}
	
	if ( empty($default) ) {
		$avatar_default = config('theme.avatar_default');
		if ( empty($avatar_default) )
			$default = url('five-includes/views/images/user.png');
		else
			$default = $avatar_default;
	}

	if ( !empty($email) )
		$email_hash = md5( strtolower( $email ) );

	if ( is_ssl() ) {
		$host = 'https://secure.gravatar.com';
	} else {
		if ( !empty($email) )
			$host = sprintf( "http://%d.gravatar.com", ( hexdec( $email_hash[0] ) % 2 ) );
		else
			$host = 'http://0.gravatar.com';
	}

	if ( 'gravatar_default' == $default )
		$default = "$host/avatar/s={$size}";
	elseif ( empty($email) )
		$default = "$host/avatar/?d=$default&amp;s={$size}";

	// build the img tag
	if (isset($user->avatar) && $user->avatar) {
		$avatar = "<img alt='{$safe_alt}' src='".url($user->avatar)."' class='avatar avatar-{$size} photo avatar-default' width='{$size}' />";
	}
	elseif ( !empty($email) ) {
		$out = "$host/avatar/";
		$out .= $email_hash;
		$out .= '?s='.$size;
		$out .= '&amp;d=' . urlencode( $default );
		
		$avatar = "<img alt='{$safe_alt}' src='{$out}' class='avatar avatar-{$size} photo' width='{$size}' />";
	}
	else {
		$avatar = "<img alt='{$safe_alt}' src='{$default}' class='avatar avatar-{$size} photo avatar-default' width='{$size}' />";
	}

	return apply_filters('get_avatar', $avatar, $id_or_email, $size, $default, $alt);
}

/**
 * Function is responsible for echoing the avatar
 * 
 * @param unknown_type $id_or_email
 * @param unknown_type $size
 * @param unknown_type $default
 * @param unknown_type $alt
 */
function avatar($id_or_email = null, $size = '150', $default = '', $alt = false)
{
	if (is_null($id_or_email))
	{
		$id_or_email = FiveFactory::getUser();
	}
	echo get_avatar($id_or_email, $size, $default, $alt);
}

/**
 * Encoding passwords using SHA1
 * 
 * @param unknown_type $password
 * @return string
 */
function pw_encode( $password )
{
	$seed = NULL;
	for ($i = 1; $i <= 10; $i++)
		$seed .= substr('0123456789abcdef', rand(0,15), 1);
	
	return sha1($seed.$password.$seed).$seed;
}

/**
 * de-coding function using SHA1 
 * 
 * @param $password
 * @param $stored_value
 */
function pw_check($password, $stored_value)
{
	if (strlen($stored_value) != 50)
		return FALSE;
		
	$stored_seed = substr($stored_value,40,10);
	if (sha1($stored_seed.$password.$stored_seed).$stored_seed == $stored_value)
		return TRUE;
	else
		return FALSE;
}

/** 
 * The letter l (lowercase L) and the number 1 
 * have been removed, as they can be mistaken 
 * for each other. 
 */ 
function createRandomPassword() { 
	$chars = "abcdefghjkmnpqrstuvwxyz23456789"; 
	srand((double)microtime()*1000000); 
	$i = 0; 
	$pass = '' ; 

	while ($i <= 7) { 
		$num = rand() % 33; 
		$tmp = substr($chars, $num, 1); 
		$pass = $pass . $tmp; 
		$i++; 
	} 

	return $pass;
}



