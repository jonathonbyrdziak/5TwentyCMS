<?php 
/**
 * @Author Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package CCprocessing
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or _die("Cannot access pages directly.");

//actions
add_action('authenticate', 'auth_validation', 1, 3);
add_action('authenticate', 'interal_authentication', 20, 3);

/**
 * Function is responsible for validating the submited user
 * information
 * 
 * @param unknown_type $username
 * @param unknown_type $password
 * @param unknown_type $remember
 */
function auth_validation( $username, $password, $remember = false )
{
	//redirect if successful already
	if(is_user_logged_in()) { redirect( Router::url(array('controller'=>'user','action'=>'profile')) ); }
	
	if (!$username)
	{
		set_error( 'The Username field cannot be empty.' );
		login_has_error(1);
	}
	elseif (!is_email($username) && !preg_match('/^[[:space:]a-zA-Z0-9\'\.*#\/\\_;:\-]{6,30}$/i', $username)) 
	{
		set_error( 'The Username must have a minimum of 6 characters, with a maximum of 30;  And cannot contain certain special characters.'); 
		login_has_error(1);
	}
	
	if (!$password)
	{
		set_error( 'The Password field cannot be empty.' );
		login_has_error(1);
	}
	elseif (strlen($password) > 30 || strlen($password) < 6) 
	{ 
		set_error( 'The Password must have a minimum of 6 characters, with a maximum of 30.'); 
		login_has_error(1);
	}
}

/**
 * Function is responsible for authenticating users and redirecting 
 * them upon a succesful login.
 * 
 * @param unknown_type $username
 * @param unknown_type $password
 * @param unknown_type $remember
 */
function interal_authentication( $username, $password, $remember = false )
{
	//redirect if successful already
	if(is_user_logged_in()) { redirect( Router::url(array('controller'=>'user','action'=>'profile')) ); }
	if(login_has_error()) return false;
	
	$user = FiveTable::getInstance('user');
	if (!$user->authenticate( $username, $password ))
	{
		set_error( $user->getErrors() ); 
		login_has_error(1);
	}
	else
	{
		//success
		redirect( Router::url(array('controller'=>'user','action'=>'profile')) );
	}
}

/**
 * Function is responsible for 
 * 
 * @param unknown_type $answer
 */
function login_has_error( $answer = null )
{
	static $a;
	
	if (!isset($a))
	{
		$a = false;
	}
	
	if (!is_null($answer))
	{
		$a = true;
	}
	
	return $a;
}