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
$url = Router::parse();
$urlCat = false;
if (isset($url['category']))
	$urlCat = $url['category'];

$urlCol = false;
if (isset($url['collection']))
	$urlCol = $url['collection'];

$urlAss = false;
if (isset($url['assortments']))
	$urlAss = $url['assortments'];

if ($urlCat == 'services')
{
	$type = 'services';
	$product = FiveTable::getInstance('services');
	$products = $product->getServices( $urlCol, $urlAss );
}
else
{
	$type = 'products';
	$product = FiveTable::getInstance('products');
	$products = $product->getProducts( $urlCat, $urlCol, $urlAss );
}

if (!$products || empty($products))
{
	echo '<div class="site_errors rounded-eight">No '.ucfirst($type).' are currently in this view.</div>';
}
else
{
	foreach((array)$products as $product)
	{
		if (!$products) continue;
		require $view;
	}
}