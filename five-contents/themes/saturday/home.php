<?php show_view('header'); ?>
<div id="wrapper-container">
    <div id="wrapper">
    
	    <?php do_action('notifications'); ?>
		<?php show_view('banner-welcome'); ?>
		<?php show_view('sidebar-categories'); ?>
		
		<div id="right_container">
			<?php show_view('filters-index'); ?>
			<br/>
			<?php show_view('home-products'); ?>
			<div class="clear"></div>
			<?php show_view('sidebar-featured-display'); ?>
			<?php show_view('filters-pagination'); ?>
			<?php //echo $content; ?>
		</div>

        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<?php show_view('footer'); ?>