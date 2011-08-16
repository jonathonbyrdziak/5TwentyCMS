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
$service = FiveTable::getInstance('services');
$bizid = get_bizid(get_current_user_id());
$service->loadByBizID( $bizid );

if ($post = BRequest::get('post', false))
{
	if($service->save( $post ))
	{
		set_notice("Profile Saved.");
	}
	else
	{
		set_error( $service->getErrors() );
	}
}


require $view;