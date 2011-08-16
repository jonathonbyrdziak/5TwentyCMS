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

// initializing
$business = FiveTable::getInstance('business');
$business->loadByUserID( get_current_user_id() );

$transaction = FiveTable::getInstance('transaction');
$has = true;

if ($post = BRequest::get('post', false))
{
	if (!BRequest::getVar('agree',false)) {
		set_error( 'Please make sure that you agree with the Terms of Use.' );
		$has = false;
	}
	
	if (!$business->save( $post )) {
		set_error( $business->getErrors() );
		$has = false;
	}
	
	if(!$transaction->bind( $post, array(), false )) {
		set_error( $transaction->getErrors() );
		$has = false;
	}
	elseif(!$transaction->save( $post )) {
		set_error( $transaction->getErrors() );
		$has = false;
	} 
	
	// handling the promotional code
	$promo = false;
	if (config('merchants.promocode') && BRequest::getVar('_promocode',false) == config('merchants.promocode')) {
		$promo = true;
		$_SESSION['registration']['reqid'] = 'PROMO';
		$_SESSION['registration']['reqtoken'] = 'PROMO';
		$_SESSION['registration']['amt'] = 0.00;
	}
	
	//@todo process the credit card
	if($has && !$promo) {
		$post['amount'] = '20.00';
		$post['products'] = array(
			array(
				'name' => 'Services Registration',
				'price' => '20.00',
				'qty' => '1',
				'sku' => '02201',
			)
		);
		
		$auth = apply_filters('captureCreditCard', $post);
		if ($auth['auth'] == 'ACCEPT') 
		{  
			$query = "update cybersource set reqid='" .$auth['reqid']. "',reqtoken='" .$auth['reqtoken']. "' where id='" .$csid. "'";
			if(!mysql_query($query)) 
			{ 
				die(ERROR_MSG_CRITICAL1); 
			}
			
			$_SESSION['registration']['reqid'] = $auth['reqid'];
			$_SESSION['registration']['reqtoken'] = $auth['reqtoken'];
			$_SESSION['registration']['amt'] = BRequest::getVar('total');
		}
		else
		{ 
			set_error( $auth['errorMsg'] );
			$has = false;
		}
	}
	
	//If there's no error, then redirect
	if ($has) 
	{
		$groups =& FiveTable::getInstance('groups');
		$groups->loadByUserID( get_current_user_id() );
		$groups->business = 1;
		$groups->store();
		
		redirect( Router::url(array('controller'=>'user','action'=>'profile')) );
	}
}

require $view;