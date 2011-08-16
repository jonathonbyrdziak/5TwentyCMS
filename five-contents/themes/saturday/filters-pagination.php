<?php 

//initializing
$ratings_newest = (BRequest::getVar('s')=='r' && BRequest::getVar('r')=='n')? 'active':'';
$ratings_oldest = (BRequest::getVar('s')=='r' && BRequest::getVar('r')=='o' && !BRequest::getVar('p',false))? 'active':'';

$ratings_rating = (BRequest::getVar('s')=='r' && !BRequest::getVar('r',false))? 'active':'';
$ratings_like = (BRequest::getVar('s')=='l' && BRequest::getVar('r') == 'o')? 'active':'';

$hight_low = (BRequest::getVar('p')=='h' && BRequest::getVar('s') == 'r' && BRequest::getVar('r') == 'o')? 'active':'';

$querystring = array();
$querystring['s'] = BRequest::getVar('s');
$querystring['p'] = BRequest::getVar('p');
$querystring['r'] = BRequest::getVar('r');

$parts = explode('?',$_SERVER['REQUEST_URI']);
$uri = $parts[0];

?>
<div class="index_filters">
	<div style="width:80px;height:30px;position:relative;float:left;">
			</div>
	
	<div class="filters_buttons">
		<div class="filter_button fbl"><a href="<?php 
			
			$querystring['ppage'] = BRequest::getInt('ppage')-1;
			echo Router::normalize($uri, $querystring); 
			
		?>">Previous</a></div>
		
		
		<div class="filter_button fbr"><a href="<?php
			
			$querystring['ppage'] = BRequest::getInt('ppage')+1;
			echo Router::normalize($uri, $querystring); 
			
		?>">Next</a></div>
	</div>
</div>