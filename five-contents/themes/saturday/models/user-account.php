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

//initializing
$user = FiveTable::getInstance('user');

if (BRequest::getVar('verify', false))
{
	// LOGIN USER
	mysql_query("update user set status='active' where secToken='" .BRequest::getVar('verify', false). "'") or die(mysql_error());
	$result = mysql_query("select id,username,email,zip from user where secToken='" .BRequest::getVar('verify', false). "'") or die(mysql_error());
	$row = mysql_fetch_row($result);
	set_session($row[0],stripslashes($row[1]),stripslashes($row[2]),$row[3]);
	$user->load( get_current_user_id() );
	
	set_notice('Please make sure to update your password before continuing.');
}

//redirect if successful
if (!is_user_logged_in()) { redirect( Router::url(array('controller'=>'user','action'=>'login')) ); }

//loading the user
$user->load( get_current_user_id() );

if ($post = BRequest::get('post', false))
{
	//$user->load( get_current_user_id() );
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





