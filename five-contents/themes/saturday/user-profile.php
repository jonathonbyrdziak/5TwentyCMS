<?php show_view('header-w-wrap'); ?>
<?php show_view('sidebar-profile-left')?>

<div class="profile-content">

	<h1>dashboard</h1>
	
	<?php 
	if($user->is_business()): 
		echo get_profile_notif();
	else: 
	?>
		<div class="copy">
			<h3>Become a Vendor!</h3>
			<p>Advertise your products and services online, for only $20 a year!</p>
			<ul class="vendor-benefits">
				<li>create your web presence</li>
				<li>set up your online store in minutes</li>
				<li>merchant services are handled for you</li>
				<li>automated shipping for easy fulfillment</li>
				<li>put products in front of Portland Saturday Market shoppers</li>
			</ul>
			<a title="Link to register a company in the Portland Saturday Market services directory." alt="Link to register a company in the Portland Saturday Market services directory." href="<?php echo Router::url(array('controller'=>'user','action'=>'register','vendor')); ?>">
				<img src="http://shop.saturdaymarket.org.php5-19.dfw1-1.websitetestlink.com/img/btn_start_marketing_today.gif"/></a>
		</div>
	<?php endif; ?>
	
</div>

<?php show_view('footer-w-wrap'); ?>