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

//redirect if successful
if(is_user_logged_in()) { redirect( Router::url(array('controller'=>'user','action'=>'profile')) ); }

if (BRequest::get('post', false))
{
	$user = FiveTable::getInstance('user');
	$parts = explode(' ', BRequest::getVar('name', ''));
	
	$user->firstName = $parts[0];
	if(isset($parts[1])) {
		unset($parts[0]);
		$user->lastName = implode(' ', $parts);
	}
	
	$user->email = BRequest::getVar('email', false);
	
	if(!$user->check()) {
		set_error( $user->getErrors() );
	}
	elseif(!BRequest::getVar('agree', false)) {
		set_error( 'Make sure to agree to the Terms of Use.' );
	}
	else {
		$password = createRandomPassword();
		$user->password = pw_encode( $password );
		
		// this function also does a store procedure
		$secToken = $user->setRegistrationToken();
		$user->store();
		
		// send verification email
		$to = 'New User <' .$user->email. '>';
		$args = array(	
			'subject' => EMAIL_REGISTER_SUBJECT,
			'username' => $user->email,
			'password' => $password,
			'site_name' => SITE_NAME,
			'site_title' => SITE_TITLE,
			'verifyURL' => url("user/account?verify=$secToken")
		);
		
		$returnOutput = get_show_view('email-register', $args);
		
		// CALL SEND EMAIL
		send_email( $to, EMAIL_REGISTER_SUBJECT, $returnOutput, EMAIL_REGISTER_FROM );
		
		//success
		set_session($user->id, $user->username, $user->email, $user->zip);
		redirect( Router::url(array('controller'=>'user', 'action'=>'profile')) );
	}
}

require $view;





