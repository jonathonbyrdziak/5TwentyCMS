<?php show_view('header'); ?>
<div id="wrapper-container">
	<div id="wrapper">
		<?php show_view('sidebar-categories'); ?>
		
		<div id="right_container">
			<h3>Search Results</h3>
			<div class="pad10top">
				<?php show_view('search-results'); ?>
			</div>
		</div>
		
	</div>
	<div class="clear"></div>
</div>
<?php show_view('footer'); ?>