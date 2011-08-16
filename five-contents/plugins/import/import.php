<?php 
/**
 * @Author Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Importing
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or _die("Cannot access pages directly.");

?>
<?php show_view('header'); ?>
<div class="import-wrapper">
	<p>Please login, then choose a file type to import.</p>
	<a href="<?php echo url('import/business'); ?>"><div class="import-col">Business</div></a>
	<a href="<?php echo url('import/users'); ?>"><div class="import-col">User</div></a>
	<a href="<?php echo url('import/products'); ?>"><div class="import-col">Products</div></a>
	<div class="clear"></div>
</div>

<?php show_view('footer'); ?>