<?php 
/**
 * @Author Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package CCprocessing
 * @subpackage Internet Secure Merchant Direct
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or _die("Cannot access pages directly.");

/**
 * Request XML File 
 * 
 * This is the xml schema that we're using to request data
 * from Internet Secure Merchant Direct.
 */
defined("ITERNETSECUREXML") or define("ITERNETSECUREXML", dirname(__file__).DS."InternetSecure.xml");

/**
 * Response URL
 * 
 * Internet Secure Merchant Direct will respond back to us
 * with the results. Here's the url that we want them to respond
 * back to.
 */
$five_config = fiveConfigurations();
defined("ITERNETSECURERESPONSE") or define("ITERNETSECURERESPONSE", $five_config->base->url."?five_ajax=CCresponse");

/**
 * AUTHORIZING
 * 
 * Function is responsible for making sure that the credit 
 * card has money on it.
 * 
 * @param unknown_type $data
 * @param unknown_type $fiveConfig
 * @param unknown_type $testMode
 */
function InternetSecureAuthorize( $data, $config, $testMode )
{
	//If we're not using this gateway then exit
	if ($data['gateway'] != 'InternetSecure') return $data;
	
	//initializing
	$url = (string)$config->host;
	$requestString = InternetSecureRequest( $data, $testMode, $config );
	
	//loading
	$response = five_remote_get($url, $requestString);
	
	//houston we have a problem
	if ($response['response']['code'] != 200)
	{
		$data['error'] = $response['response']['code'];
		$data['errorMsg'] = "Payment Gateway Error :: ".$data['error']." ".$response['response']['message'];
	}
	else
	{
		$data['auth'] = $response['ACCEPT'];
	}
	
	_520($response);
	return $data;
}

/**
 * CAPTURING
 * 
 * Function is responsible for charging the credit card.
 * 
 * @param unknown_type $data
 * @param unknown_type $fiveConfig
 * @param unknown_type $testMode
 */
function InternetSecureCapture( $data, $config, $testMode )
{
	//If we're not using this gateway then exit
	if ($data['gateway'] != 'InternetSecure') return $data;
	
	//initializing
	$url = (string)$config->host;
	$requestString = InternetSecureRequest( $data, $testMode, $config, 00 );
	
	//loading
	$response = five_remote_get($url, $requestString);
	
	//houston we have a problem
	if ($response['response']['code'] != 200)
	{
		$data['error'] = $response['response']['code'];
		$data['errorMsg'] = "Payment Gateway Error :: ".$data['error']." ".$response['response']['message'];
	}
	else
	{
		$data['auth'] = $response['ACCEPT'];
	}
	
	return $data;
}

/**
 * Function is responsible for forming our request string
 * 
 * @param $data
 * @param $testMode
 * @param $config
 * @param $transType
 */
function InternetSecureRequest( $data, $testMode, $config, $transType = 02 )
{
	//initializing
	$request = simplexml_load_file(ITERNETSECUREXML);
	
	$request->MerchantNumber = $request->GatewayID = (string)$config->MerchantNumber;
	$request->xxxIOPApprovalURL = ITERNETSECURERESPONSE;
	$request->xxxIOPDeclinedURL = ITERNETSECURERESPONSE;
	
	//The Transaction Type to be processed. 
	// 00 for "Purchase"
	// 02 for "Pre-authorization"
	// 11 for "Online Refund"
	// 15 for "Pre-Auth Completion"
	// 06 for "Online Void"
	// 21 for "Cancel Pre-Auth" (sent to https://direct.internetsecure.com/CancelPreauth)
	// 22 for "Authenticate Card"
	$request->xxxTransType = $transType;
	
	//This is where we signify that we're setting up a test
	// Price::Qty::Product Code::Description::Flags
	// -15.95::1::001::Refund of Shipping on Backordered Item::{TEST}
	$products = '';
	if (is_array($data['products']))
	{
		foreach((array)$data['products'] as $product)
		{
			$products .= "{$product['price']}::{$product['qty']}::{$product['sku']}::{$product['name']}::".(($testMode)?'{TEST}':'');
		}
	}
	$request->Products = $products;
	
	$request->xxxMerchantCustomerID = $data['user_id'];
	$request->xxxMerchantInvoiceNumber = $data['invoice_id'];
	
	$request->xxxShippingName = $request->xxxName = $data['fname'].' '.$data['lname'];
	$request->xxxShippingCompany = $request->xxxCompany = '';
	$request->xxxShippingAddress = $request->xxxAddress = $data['addr1'].' '.$data['addr2'];
	$request->xxxShippingCity = $request->xxxCity = $data['fname'];
	$request->xxxShippingProvince = $request->xxxProvince = $data['fname'];
	$request->xxxShippingPostal = $request->xxxPostal = $data['fname'];
	$request->xxxShippingCountry = $request->xxxCountry = $data['fname'];
	$request->xxxShippingPhone = $request->xxxPhone = $data['fname'];
	$request->xxxShippingEmail = $request->xxxEmail = $data['fname'];
	
	$request->xxxCardType = $data['ccType'];
	$request->xxxCard_Number = $data['ccNum'];
	$request->xxxCCMonth = $data['ccExpMM'];
	$request->xxxCCYear = $data['ccExpYY'];
	$request->CVV2 = $data['ccSecCode'];
	$request->CVV2Indicator = (!empty($request->CVV2))? 1:0;
	
	$requestString = "xxxRequestMode=X&xxxRequestData={$request->asXml()}";
	return $requestString;
}

