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

/**
 * Function is responsible for preparing the default data and 
 * running it through the CCpreprocessing filter. Plugins 
 * may then catch this and process the credit card information.
 * 
 *    DEFINING Authorization Only
 * 
 * This transaction type is sent for authorization only. The 
 * transaction will not be sent for settlement until the credit 
 * card transaction type Prior Authorization and Capture is 
 * submitted, or the transaction is submitted for capture manually 
 * in the Merchant Interface.
 * 
 * If action for the Authorization Only transaction is not taken on 
 * the payment gateway within 30 days, the authorization expires and 
 * is no longer available for capture. A new Authorization Only 
 * transaction would then have to be submitted to obtain a new 
 * authorization code.
 * 
 * You can submit Authorization Only transactions if you want to 
 * verify the availability of funds on the customer’s credit card 
 * before finalizing the transaction. This transaction type can also 
 * be submitted in the event that you do not currently have an item 
 * in stock or you want to review orders before shipping goods.
 * 
 * @param $data
 * @return array $response
 */
function authorizeCreditCard( $data )
{
	$response = CCprocessing( $data, 'pre' );
	return $response;
}

/**
 * Function is responsible for preparing the default data and 
 * running it through the CCprocessing filter. Plugins 
 * may then catch this and process the credit card information.
 * 
 *      DEFINING Capture Only
 *      
 * This transaction type is used to complete a previously authorized 
 * transaction that was not originally submitted through the payment 
 * gateway or that required voice authorization.
 * 
 * The payment gateway accepts Capture Only transactions if the 
 * following conditions are met:
 * 
 *     * The transaction is submitted with the valid authorization 
 *     code issued to the merchant to complete the transaction.
 *     
 *     * The transaction is submitted with the customer’s full credit 
 *     card number and expiration date.
 * 
 * @param $data
 * @return array $response
 */
function CCprocessing( $data, $filter = '' )
{
	//initializing
	global $five_config;
	$dataDefaults = array(
		//contact information
		'user_id' => '',
		'fname' => '',
		'lname' => '',
		'email' => '',
		
		//billing address
		'addr1' => '',
		'addr2' => '',
		'city' => '',
		'state' => '',
		'zc1' => '',
		'zc2' => '',
		
		//credit card information
		'ccNum' => '',
		'ccExpMM' => '',
		'ccExpYY' => '',
		'ccType' => '',
		'ccSecCode' => '',
		
		//invoicing information
		'gateway' => (string)$five_config->merchants->gateway,
		'invoice_id' => '',
		'amount' => '0',
		'currency' => 'USD',
		'products' => 
		array(
			array(
				'name' => '',
				'price' => '',
				'qty' => '',
				'sku' => '',
			)
		),
		
		//response variables
		'auth' => 'ERROR', // ACCEPT,ERROR,REJECT,REVIEW
		'authDesc' => '',
		'reqid' => '',
		'reqtoken' => '',
		'refid' => '',
	
		//appriopriate error reporting
		'error' => true,
		'errorMsg' => 'N/A',
	);
	
	$data = five_shortcode_atts($dataDefaults, $data);
	
	$merchant = (string)$five_config->merchants->gateway;
	$merchantConfig = $five_config->merchants->$merchant;
	
	//run it through the processing filter and return the results 
	$response = apply_filters("CC{$filter}processing", $data, $merchantConfig, ($five_config->merchants->test_mode));
	return $response;
}

