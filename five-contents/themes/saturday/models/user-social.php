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
if(!is_user_logged_in()) { redirect( Router::url(array('controller'=>'user','action'=>'login')) ); }

//initializing
$user = FiveTable::getInstance('user');
$user->load( get_current_user_id() );

if ($post = BRequest::get('post', false))
{
	if($user->save( $post ))
	{
		set_notice("Profile Saved.");
	}
	else
	{
		set_error( $user->getErrors() );
	}
}


require $view;