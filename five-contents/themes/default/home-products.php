<?php if ($type == 'services'): ?>
	<?php 
	$business = FiveTable::getInstance('business'); 
	$business->loadByBizId($product->bizid);
	?>

	<div class="index_servmain_container" style="margin-right:{margin};">
		<h3 class="nocufon">
			<a title="<?php echo $business->name; ?>" alt="<?php echo $business->name; ?>" href="<?php echo $business->getUrl(); ?>">
				<?php echo $business->name; ?>
			</a>
		</h3>
	    <a title="<?php echo $business->name; ?>" alt="<?php echo $business->name; ?>" href="<?php echo $business->getUrl(); ?>">
	    	<img align="left" src="http://www.publicmarketspace.com/img/business/biz_59574c62e5d76b292icon.jpg" alt="<?php echo $business->name; ?>" title="<?php echo $business->name; ?>" border="0">
	    </a>
	    <p class="desc"><?php echo $business->promo; ?></p>
	</div>

<?php else: ?>

	<div class="index_prod_container">
	    <a title="<?php echo $product->prod_name; ?>" alt="<?php echo $product->prod_name; ?>" href="<?php echo url('product/'.$product->id.'/'.$product->prod_name); ?>"><img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->prod_name; ?>" title="<?php echo $product->prod_name; ?>" width="135" height="110" border="0"></a>
		<h3 class="nocufon"><a title="<?php echo $product->prod_name; ?>" alt="<?php echo $product->prod_name; ?>" href="<?php echo url('product/'.$product->id.'/'.$product->prod_name); ?>"><?php echo $product->prod_name; ?></a></h3>
	    <p class="amount">$<?php echo $product->price; ?></p>
	    <p class="like_it"><a href="javascript:void(0);" class="like_it_btn" id="product-<?php echo $product->id; ?>"><span></span></a> </p>
		<div class="clear"></div>
		<div class="container_hover">
			<div class="hover_button hover_details"><a href="<?php echo $product->getUrl(); ?>">Details</a></div>
			<div class="hover_button hover_buy"><a href="<?php echo $product->getCartUrl(); ?>">Buy</a></div>
		</div>
	</div>

<?php endif; ?>