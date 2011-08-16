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

/**
 * 
 * @param unknown_type $keys
 * @param unknown_type $values
 * @return Ambigous <multitype:, unknown>
 */
function import_merge_array( $keys, $values )
{
	$new = array();
	foreach ($keys as $k => $key)
	{
		$new[$key] = $values[$k];
	}
	return $new;
}

$business = FiveTable::getInstance('user');
$headers = $business->getProperties();
?>
<?php show_view('header'); ?>

<div class="import-wrapper">
	<?php if (!$import): ?>
	<p>Only CSV files are accepted. Make sure that your file headers (row 1) match these variables (case-sensitive):</p>
	<ul>
		<?php foreach ((array)$headers as $property => $header): ?>
		<li><?php echo $property; ?></li>
		<?php endforeach; ?>
	</ul>
	<form action='' method='post' enctype='multipart/form-data'>
		<input name='import' type='file' />
		<button name='submit' type='submit'>Submit</button>
	</form>
	<?php else:
	if (($handle = fopen(ABSPATH.$import, "r")) === FALSE): ?>
		<p>You're file seems to be corrupt, please email it to jon@5twentystudios.com so that it can be fixed.</p>
	
	<?php else:
		$headers = fgetcsv($handle, 1000, ",");
		
		$row = 2;
		$rows = array();
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	    	$rows[] = import_merge_array($headers, $data);
	    }
	    fclose($handle);
	    
	    foreach ((array)$rows as $row): 
		    $business = FiveTable::getInstance('business');
		    $business->bind($row);
		    
		    $business->dateCreated = strtotime($business->dateCreated);
		    
		    $business->import_id = $business->id;
		    $business->id = null;
		    
		    if ($business->store()):
		    	?><p>Saved <?php echo $business->name; ?></p><?php 
		    else: 
		    	?><p>Cannot save <?php echo $business->name; ?></p><?php 
		    	$e = implode('<br/>',$business->getErrors());
		    	?><p><?echo $e; ?></p><?php 
		    endif;
		    
	    endforeach;
	    
		endif;
	?>
	<?php endif; ?>
	<div class="clear"></div>
</div>
	<div class="clear"></div>

<?php show_view('footer'); ?>