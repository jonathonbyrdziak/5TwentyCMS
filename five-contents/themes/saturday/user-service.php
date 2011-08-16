<?php show_view('header-w-wrap'); ?>
<?php show_view('sidebar-profile-left')?>

<div class="profile-content">

	<h1>service information</h1>
	<?php 
		global $cl, $page;
		require_once ABSPATH.'inc'.DS.'func_service.php';
		
		// declaring globals
		$page = 'service';
		
		$cl = new contentLogic();
		$main = new main($page);
		echo $main->get_output();
	?>
</div>

<?php show_view('footer-w-wrap'); ?>