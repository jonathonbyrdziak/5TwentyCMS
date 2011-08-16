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

$product = FiveTable::getInstance('products');
$products = $product->getLike( BRequest::getVar('query') );

if (BRequest::getVar('query', false) && $products):
	foreach ((array)$products as $product)
	{
		if (!$product) continue;
		require $view;
	}
	
else:
?>
	<p>Sorry, we couldn't find any results.</p>
<?php 
endif;