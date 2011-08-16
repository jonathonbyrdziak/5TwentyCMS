<?php 

//initializing
$ratings_newest = (BRequest::getVar('s')=='r' && BRequest::getVar('r')=='n')? 'active':'';
$ratings_oldest = (BRequest::getVar('s')=='r' && BRequest::getVar('r')=='o' && !BRequest::getVar('p',false))? 'active':'';

$ratings_rating = (BRequest::getVar('s')=='r' && !BRequest::getVar('r',false))? 'active':'';
$ratings_like = (BRequest::getVar('s')=='l' && BRequest::getVar('r') == 'o')? 'active':'';

$hight_low = (BRequest::getVar('p')=='h' && BRequest::getVar('s') == 'r' && BRequest::getVar('r') == 'o')? 'active':'';

$parts = explode('?',$_SERVER['REQUEST_URI']);
$url = Router::normalize($parts[0]);

?>
<div class="index_filters">
	<div style="width:80px;height:30px;position:relative;float:left;">
		<?php if ($ratings_newest || $ratings_oldest || $ratings_rating || $ratings_like || $hight_low): ?>
		<div class="filters_button">
			<div class="filter_button filter_button_first"><a href="<?php echo $url; ?>">Clear</a></div>
		</div>
		<?php endif; ?>
	</div>
	
	<div class="filters_buttons">
		<div class="filter_button fbl <?php echo $ratings_newest; ?>"><a href="<?php echo $url; ?>?r=n&s=r">Newest</a></div>
		<div class="filter_button fbr <?php echo $ratings_oldest; ?>"><a href="<?php echo $url; ?>?r=o&s=r">Oldest</a></div>
	</div>
	<div class="filters_buttons">
		<div class="filter_button fbl <?php echo $ratings_rating; ?>"><a href="<?php echo $url; ?>?s=r">Rating</a></div>
		<div class="filter_button fbr <?php echo $ratings_like; ?>"><a href="<?php echo $url; ?>?s=l&r=o">Likes</a></div>
	</div>
	<div class="filters_button_last">
		<div class="filter_button filter_one <?php echo $hight_low; ?>"><a href="<?php echo $url; ?>?p=h&s=r&r=o">High to Low</a></div>
	</div>
</div>