<?php show_view('header-w-wrap'); ?>

<?php show_view('sidebar-profile-left')?>

<div class="profile-content">

	{returnmsg}
	<h1>Orders</h1>
	<ul>
		<li style="border: none; border-bottom: 1px solid #F1F1F1; background: none; margin-bottom: 5px;">
	    	<span class="invoice"><strong>view invoice</strong></span>
	        <span class="order"><strong>order</strong></span>
	        <span class="date"><strong>date</strong></span>
	        <span class="name" style="width: 219px;"><strong>from</strong></span>
	        <span class="atm"><strong>price</strong></span>
	        <div class="clear"></div>
	    </li>
		<?php show_view('user-orders-order');?>
	</ul>

</div>

<?php show_view('footer-w-wrap'); ?>