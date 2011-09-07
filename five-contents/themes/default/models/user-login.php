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

if (BRequest::get('post', false))
{
	do_action('authenticate', BRequest::getVar('username'), BRequest::getVar('passwd'), BRequest::getVar('remember') );
}

//redirect if successful
if(is_user_logged_in()) { redirect( Router::url(array('controller'=>'user','action'=>'profile')) ); }

require $view;