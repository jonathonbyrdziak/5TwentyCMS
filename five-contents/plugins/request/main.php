<?php 
/**
 * @Author Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package PublicMarketSpace
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or _die("Cannot access pages directly.");

class homepage {
	
	var $output;
	
	// CONSTRUCT
	function old__construct() 
	{
		global $path_site;
		global $cl; // CONTENT LOGIC FUNCTIONS
		global $meta_desc;
		global $meta_keywords;
		global $title;

		$action = BRequest::getCmd('action');
		$method = BRequest::getCmd('method');
		$call = BRequest::getCmd('call'); // a call is an ajax call.

		// TRACK LAST PAGE
		// $last = lastURL();
		$returnmsg = NULL;
		
		// INIT OBJECTS
		if (!BRequest::getVar('category',false)) {
			unset($_SESSION['assortment'],$_SESSION['category'],$_SESSION['collection']); 
		}
		if (BRequest::getVar('category',false)) { 
			$title .= ucwords(BRequest::getString('category')) . ' - '; 
			unset($_SESSION['assortment'],$_SESSION['collection']); 
		}
		if (BRequest::getVar('collection',false)) { 
			$title .= ucwords(BRequest::getString('collection')) . ' - ';
			unset($_SESSION['assortment']); 
		}
		if ((BRequest::getVar('category',false) && !isset($_SESSION['category'])) || (BRequest::getVar('category',false) && $_SESSION['category'] != BRequest::getString('category') )) { 
			$_SESSION['category'] = BRequest::getVar('category'); 
		}
		if ((BRequest::getVar('collection',false) && !isset($_SESSION['collection'])) || (BRequest::getVar('collection',false) && $_SESSION['collection'] != BRequest::getVar('collection') )) { 
			$_SESSION['collection'] = BRequest::getVar('collection'); 
		}
		if ( (BRequest::getVar('assortment',false) && !isset($_SESSION['assortment'])) || (BRequest::getVar('assortment',false) && $_SESSION['assortment'] != BRequest::getVar('assortment') )) { 
			$title .= ucwords(BRequest::getString('assortment')) . ' - ';
			$_SESSION['assortment'] = BRequest::getVar('assortment'); 
		}
		
		// AJAX CALLS START \\
		if ($call == 'sl') // AJAX CALL - SET LIKE
		{
			if (( BRequest::getVar('i',false) and preg_match('/^[0-9]{1,20}$/i',BRequest::getInt('i')) ) && ( isset($method) and preg_match('/^[a-z]{4,20}$/i',$method) )) {
				$return = $this->set('like');
			}
			else
			{
				$return = 0;
			}
			print($return);
			exit();

		}
		if ($call == 'sr') // AJAX CALL - SET Rating
		{
			if (( BRequest::getVar('i',false) and preg_match('/^[0-9]{1,20}$/i',BRequest::getInt('i') )) &&( isset($method) and preg_match('/^[a-z]{4,20}$/i',$method) )) 
			{
				$return = $this->set('rating');
			}
			else
			{
				$return = 0;
			}
			print($return);
			exit();

		}
		
		/*
		
		// CALL CONTENT AND REPLACE TAGS INSIDE
		$content = ABSPATH.'/inc/cb/index.inc';
		$returnOutput = new main_output($content);

		// replace tags from template
		@ $returnOutput->replace_tags(array(	
			'path_site' => SITE_BASEURL,
			'side_nav' => $cl->get_sideNav(),
			'collections' => $cl->get_boxOne('collections'),
			'assortments' => $cl->get_boxOne('assortments'),
			'sm_icons_index' => $cl->get_smicons('index'),
			'news_events' => $cl->get_boxTwo('news'),
			'this_month' => '',
			'advertise_banner' => rand(1, 2),
			'think_global_shop_local' => $cl->get_boxSeven('tgsl'),
			'pm_bill_of_rights' => $cl->get_boxSeven('bor'),
			'our_favorite_things' => '', // $cl->get_boxSeven('oft'),
			'category_calendar' => $cl->get_boxSeven('cm'),
			'banner' => $cl->get_banner(),
			'content' => $this->get_content()
			//'page_title' => $page_title_txt,
		));
		// Call the output
		$this->output = $returnOutput->output;*/
	}
	
	// get output function
	function get_output() 
	{
		return $this->output;
	}
	
	// SET FUNCTIONS \\
	function set($type='like')
	{
		if($type == 'like')
		{
			if(!isset($_SESSION['user']['id'])) { return '2'; }
			
			$userid =& $_SESSION['user']['id'];
			$item =& BRequest::getVar('method');
			$itemid =& BRequest::getInt('i');
			$date = time();
			
			$result = mysql_query("select id from `likes` where `userid`='" .$userid. "' and item='" .$item. "' and itemid='" .$itemid. "'");
			if(mysql_num_rows($result) < 1){ $first = "insert into "; $last=NULL; } else {  $first = "update "; $last = " where itemid='" .$itemid. "' and item='" .$item. "' and userid='" .$userid. "'";  }
			$query = $first."`likes` set
					`userid` = '" .$userid. "',
					`item`='" .$item. "',
					`itemid`='" .$itemid. "',
					`like`='1',
					`date_added` = '" .$date. "'
				".$last;
			if(mysql_query($query)) { return '1'; } else { return '0'; }
		}
		if($type == 'rating')
		{
			if(!isset($_SESSION['user']['id']) || !BRequest::getVar('rating',false)) { return '2'; }
			
			$userid = $_SESSION['user']['id'];
			$item = BRequest::getVar('method');
			$itemid = BRequest::getVar('i');
			$date = time();
			
			$update = "update ratings set rating='" .BRequest::getVar('rating'). "',date_added='" .$date. "' where item='" .$item. "' and itemid='" .$itemid. "' and userid='" .$userid. "'";
			$result = mysql_query($update) or die(mysql_error());
			if(mysql_affected_rows() == 0)
			{
				$query = "insert into ratings set rating='" .BRequest::getVar('rating'). "', item='" .$item. "', itemid='" .$itemid. "', userid='" .$userid. "', date_added='" .$date. "'";
				if(mysql_query($query)) { return '1'; } else { return '0'; }
			}
			else { return '1'; }
		}
	}
	
	// get products listing
	function get_products($nrpp) 
	{
		// START INIT VARS \\
		global $path_site;
		$return = NULL;
		$append_url = NULL;
		$list = NULL;
		$page = (BRequest::getVar('ppage',false) and preg_match('/^[0-9]{1,999}$/i',BRequest::getVar('ppage')))? BRequest::getInt('ppage') : 1 ;

		// $_REQUEST['s'] = sort and value=r is ratings, value=l is likes
		if (BRequest::getVar('s') == 'l') { $SORT = ", sum(l.`like`) desc"; $append_url .= '&s=l'; }
		else{ $SORT = NULL; }
		// $_REQUEST['p'] = price and value=h is highest to lowest, value=l is lowest to highest
		if(BRequest::getVar('p')== 'h') { $PRICE = ", p.price desc"; $append_url .= '&p=h';}
		else if (BRequest::getVar('p') == 'l') { $PRICE = ", p.price asc"; $append_url .= '&p=l'; }
		else{ $PRICE = NULL; }
		// $_REQUEST['r'] = release and value=n is latest to oldest released, value=o is oldest to newest
		if(BRequest::getVar('r',false) == 'n') { $RELEASE = ",p.dateAdded desc"; $append_url .= '&r=n';}
		else if (BRequest::getVar('r',false) == 'o') { $RELEASE = ",p.dateAdded asc"; $append_url .= '&r=o'; }
		else { $RELEASE = NULL; }
		// RANDOM TRIGGER IF NO SORTING SET
		$RAND = NULL;
		if(empty($SORT) && empty($PRICE) && empty($RELEASE)) { $RAND='RAND()'; } 
		else { 
			if(!empty($SORT)) { $SORT = substr($SORT,1); }
			else if(!empty($PRICE)) { $PRICE = substr($PRICE,1); }
			else if(!empty($RELEASE)) { $RELEASE = substr($RELEASE,1); }
		}

		$startof = (($page * $nrpp)-$nrpp)+1;
		$endof = ($page * $nrpp);
		
		$append_url = (isset($_SESSION['category']))? $append_url. '&category='.urlencode($_SESSION['category']) : $append_url ;
		$append_url = (isset($_SESSION['collection']))? $append_url. '&collection='.urlencode($_SESSION['collection']) : $append_url ;
		$append_url = (isset($_SESSION['asssortment']))? $append_url. '&asssortment='.urlencode($_SESSION['asssortment']) : $append_url ;
		$append_url = (BRequest::getVar('spage',false))? $append_url. '&spage='.BRequest::getVar('spage') : $append_url ;
		// END INIT VARS \\
		
		// GO \\
		if (isset($_SESSION['assortment']))
		{
			$catid = get_catid_from_name(html_entity_decode(urldecode($_SESSION['category'])));
			$colid = get_colid_from_name(html_entity_decode(urldecode($_SESSION['collection'])));
			$asstid = get_asstid_from_name(html_entity_decode(urldecode($_SESSION['assortment'])));
			$cacid = get_cacid($catid,$colid);
			$ccaid = get_ccaid($cacid,$asstid);
			
			$query = "select p.id as id,p.prod_name as name,p.price as price, p.flag_proh, i.filepath as fp, i.file as f from rel_prod_cca rpcca
						join products p on rpcca.prodid=p.id
						left join images i on p.id = i.itemid
						left join business b on p.bizid=b.id
						left join likes l on p.id=l.itemid
						left join ratings r on p.id=r.itemid
							where i.item='prodThumb' and i.description='primary'" .$LOGIC_FAV. " and p.status='active' and rpcca.catid='" .$catid. "' and rpcca.cacid='" .$cacid. "' and rpcca.ccaid='" .$ccaid. "' and ( p.qty > '0' or p.flag_proh = '1')
								group by p.id
								order by ".$RAND.$SORT.$PRICE.$RELEASE;
		}
		elseif (isset($_SESSION['collection']))
		{
			$catid = get_catid_from_name(html_entity_decode(urldecode($_SESSION['category'])));
			$colid = get_colid_from_name(html_entity_decode(urldecode($_SESSION['collection'])));
			$cacid = get_cacid($catid,$colid);
			
			$query = "select p.id as id,p.prod_name as name,p.price as price, p.flag_proh, i.filepath as fp, i.file as f from rel_prod_cca rpcca
						join products p on rpcca.prodid=p.id
						left join images i on p.id = i.itemid
						left join business b on p.bizid=b.id
						left join likes l on p.id=l.itemid
						left join ratings r on p.id=r.itemid
							where i.item='prodThumb' and i.description='primary' and p.status='active' and rpcca.catid='" .$catid. "' and rpcca.cacid='" .$cacid. "' and ( p.qty > '0' or p.flag_proh = '1')
								group by p.id
								order by ".$RAND.$SORT.$PRICE.$RELEASE;
		}
		elseif (BRequest::getVar('category',false) <> 'favorites')
		{
			$catid = get_catid_from_name(html_entity_decode(urldecode( BRequest::getVar('category', false, 'session') )));
			$query = "select p.id as id,p.prod_name as name,p.price as price, p.flag_proh, i.filepath as fp, i.file as f from rel_prod_cca rpcca
						join products p on rpcca.prodid=p.id
						left join images i on p.id = i.itemid
						left join business b on p.bizid=b.id
						left join likes l on p.id=l.itemid
						left join ratings r on p.id=r.itemid
							where i.item='prodThumb' and i.description='primary' and p.status='active' and rpcca.catid='" .$catid. "' and ( p.qty > '0' or p.flag_proh = '1')
								group by p.id
								order by ".$RAND.$SORT.$PRICE.$RELEASE;
		}
		else
		{
			$LOGIC_FAV = (BRequest::getVar('category') == 'favorites')? " and l.`like` > 0" : " and b.flag_feat='1'" ;  
			$query = "select p.id as id,p.prod_name as name,p.price as price, p.flag_proh, i.filepath as fp, i.file as f,sum(l.`like`) as `likes` from products p 
				left join images i on p.id = i.itemid
				left join business b on p.bizid=b.id
				left join likes l on p.id=l.itemid
				left join ratings r on p.id=r.itemid
						where i.item='prodThumb' and i.description='primary' and p.showcase='1' and p.status='active'" .$LOGIC_FAV. " and ( p.qty > '0' or p.flag_proh = '1')
							group by p.id
							order by ".$RAND.$SORT.$PRICE.$RELEASE;
		}	
		
		//CACHING SEARCH RESULTS FOR THE LAST 30 MINUTES OF SESSION
		if (!isset($_SESSION['indexProdData'][$query]) || $_SESSION['indexProdData'][$query]['time'] < (time() - (30*60)))
		{
			$result = mysql_query($query) or die(mysql_error().'<br />'.$query);
			$_SESSION['indexProdData'][$query]['time'] = time();
			$_SESSION['indexProdData'][$query]['count'] = mysql_num_rows($result);
			$i=0;
			if( $_SESSION['indexProdData'][$query]['count'] > 0 )
			{
				while($row = mysql_fetch_assoc($result))
				{
					$_SESSION['indexProdData'][$query]['data'][$i] = $row;	
					$i++;
				}
			}
			else
			{
				$_SESSION['indexProdData'][$query]['data'] = NULL;
			}
		}

		$data = $_SESSION['indexProdData'][$query]['data'];
		if(!empty($data))
		{
			$count_total = $_SESSION['indexProdData'][$query]['count'];
			
			$num_pages = ceil($count_total / $nrpp);
			$prev = ($page > 1)? '<a href="' .SITE_BASEURL.'index.php?ppage=' .($page-1).$append_url. '">&laquo; previous</a>' : NULL ;		
			$next = ($page < $num_pages)? '<a href="' .SITE_BASEURL.'index.php?ppage='.($page+1).$append_url. '">next &raquo;</a>' : NULL ;
			$pn_divide = (!empty($prev) and !empty($next))? ' | ' : NULL ;
			
			if($page > 1) { $y = (($page -1) * $nrpp); $z = ($page * $nrpp); }
			else { $y = 0; $z = $nrpp; }

			while($y < $z)
			{
				if(isset($data[$y]['name']))
				{
					$seoPhrase = preg_replace('/[[:punct:]]*/i','',stripslashes($data[$y]['name']));
					$seoPhrase = preg_replace('/[[:space:]]+/i','-',$seoPhrase);
					$seoPhrase = preg_replace('/-{0,1}$/i','',$seoPhrase);
					
					$prod_price = number_format($data[$y]['price'],2,'.',',');
					if($data[$y]['flag_proh'] == 1)
					{  
						if($data[$y]['price'] < .01) { $prod_price = ' CFP'; }
						$buy_href = SITE_BASEURL.'product/'.$data[$y]['id'].'/'.$seoPhrase;
					}
					else {  $buy_href = SITE_BASEURL.'cart.php?action=add&i='.$data[$y]['id'];	}
					
					$numlikes = (!isset($data[$y]['likes']) || $data[$y]['likes'] < 1)? '' : '('.$data[$y]['likes'].')' ;
					$list .= replace_hh('HH-PM-INDEX-PROD1',
						array(
							'path_site' => SITE_BASEURL,
							'prod_alt' => stripslashes($data[$y]['name']),
							'buy_alt' => 'BUY '.stripslashes($data[$y]['name']),
							'title' => truncate_hard(stripslashes($data[$y]['name']),23),
							'amt' => $prod_price,
							'num_likes' => $numlikes,
							'image_src' => SITE_BASEURL.$data[$y]['fp'].$data[$y]['f'],
							'prod_href' => SITE_BASEURL.'product/'.$data[$y]['id'].'/'.$seoPhrase,
							//'product_src' => SITE_BASEURL.'product.php?id='.$row['id'],
							'product_id' => $data[$y]['id'],
							'buy_href' => $buy_href			
						)
					);
				}
				$y++;
			}
			$endof = ($count_total < $endof)? $count_total : $endof ;	
			$return = replace_output(
			file_get_contents($path_site.'inc/cb/indexProducts.inc'),
			array(
				'start' => $startof,
				'end' => $endof,
				'total' => $count_total,
				'prevnext'=> $prev.$pn_divide.$next,
				'products' => $list			
			));
		}
		else
		{
			$return = '<div class="products_box" style="width:440px;padding:20px;">'.ERROR_MS_PROD1.'</div>';
		}
		
		return $return;
	}
	// get and set filters
	function get_filters() 
	{
		// INIT VARS
		global $path_site;
		$prefix_url = SITE_BASEURL.'index.php';
		$return = NULL;
		$append_url = NULL;

		// CREATE THE NEW URL
		// $_REQUEST['s'] = sort and value=r is ratings, value=l is likes
		if(BRequest::getVar('s') == 'r') { $append_url .= '&s=r';   }
		else if (BRequest::getVar('s') == 'l') { $append_url .= '&s=l'; }
		// $_REQUEST['p'] = price and value=h is highest to lowest, value=l is lowest to highest
		if(BRequest::getVar('p') == 'h') { $append_url .= '&p=h';}
		else if (BRequest::getVar('p') == 'l') { $append_url .= '&p=l'; }
		// $_REQUEST['r'] = release and value=n is latest to oldest released, value=o is oldest to newest
		if(BRequest::getVar('r') == 'n') { $append_url .= '&r=n';}
		else if (BRequest::getVar('r') == 'o') { $append_url .= '&r=o'; }
		// RANDOM TRIGGER IF NO SORTING SET
		$append_url = (BRequest::getVar('ppage',false) and preg_match('/^[0-9]{1,999}$/i',BRequest::getVar('ppage')))? $append_url.'&ppage='.BRequest::getVar('ppage') : $append_url ;
		$append_url = (BRequest::getVar('spage',false) and preg_match('/^[0-9]{1,999}$/i',BRequest::getVar('spage')))? $append_url.'&spage='.BRequest::getVar('spage') : $append_url ;
		$append_url = (isset($_SESSION['category']))? $append_url. '&category='.urlencode($_SESSION['category']) : $append_url ;
		$append_url = (isset($_SESSION['collection']))? $append_url. '&collection='.urlencode($_SESSION['collection']) : $append_url ;
		$append_url = (isset($_SESSION['asssortment']))? $append_url. '&asssortment='.urlencode($_SESSION['asssortment']) : $append_url ;
		
		// CREATE THE FILTERS OUTPUT
		// SORT VARIABLE
		if(BRequest::getVar('s') == 'r') 
		{
			$temp_url = preg_replace('/&s=r/i','',$append_url);
			$new_url = (empty($temp_url))? '?s=l' : '?s=l'.$temp_url ;
			$clear_url = (empty($temp_url))? '' : '?'.substr($temp_url,1) ;
			$SORT = replace_hh('HH-PM-INDEX-FILTER_SORT1',array(
						'clear_url' => $prefix_url.$clear_url,
						'new_url' => $prefix_url.$new_url
					));
		}
		else if (BRequest::getVar('s') == 'l') 
		{
			$temp_url = preg_replace('/&s=l/i','',$append_url);
			$new_url = (empty($temp_url))? '?s=r' : '?s=r'.$temp_url ;
			$clear_url = (empty($temp_url))? '' : '?'.substr($temp_url,1) ;
			$SORT = replace_hh('HH-PM-INDEX-FILTER_SORT2',array(
						'clear_url' => $prefix_url.$clear_url,
						'new_url' => $prefix_url.$new_url
					));
		}
		else
		{
			$r_url = (is_null($append_url))? '?s=r' : '?s=r'.$append_url;
			$l_url = (is_null($append_url))? '?s=l' : '?s=l'.$append_url;
			$SORT = replace_hh('HH-PM-INDEX-FILTER_SORT3',array(
						'r_url' => $prefix_url.$r_url,
						'l_url' => $prefix_url.$l_url
					));
		}
		// RELEASE VARIABLE
		if(BRequest::getVar('r') == 'n') 
		{
			$temp_url = preg_replace('/&r=n/i','',$append_url);
			$new_url = (empty($temp_url))? '?r=o' : '?r=o'.$temp_url ;
			$clear_url = (empty($temp_url))? '' : '?'.substr($temp_url,1) ;
			$RELEASE = replace_hh('HH-PM-INDEX-FILTER_REL1',array(
						'clear_url' => $prefix_url.$clear_url,
						'new_url' => $prefix_url.$new_url
					));
		}
		else if (BRequest::getVar('r') == 'o') 
		{
			$temp_url = preg_replace('/&r=o/i','',$append_url);
			$new_url = (empty($temp_url))? '?r=n' : '?r=n'.$temp_url ;
			$clear_url = (empty($temp_url))? '' : '?'.substr($temp_url,1) ;
			$RELEASE = replace_hh('HH-PM-INDEX-FILTER_REL2',array(
						'clear_url' => $prefix_url.$clear_url,
						'new_url' => $prefix_url.$new_url
					));
		}
		else
		{
			$n_url = (is_null($append_url))? '?r=n' : '?r=n'.$append_url;
			$o_url = (is_null($append_url))? '?r=o' : '?r=o'.$append_url;
			$RELEASE = replace_hh('HH-PM-INDEX-FILTER_REL3',array(
						'n_url' => $prefix_url.$n_url,
						'o_url' => $prefix_url.$o_url
					));
		}
		// PRICE VARIABLE
		if(BRequest::getVar('p') == 'h') 
		{
			$temp_url = preg_replace('/&p=h/i','',$append_url);
			$new_url = (empty($temp_url))? '?p=l' : '?p=l'.$temp_url ;
			$clear_url = (empty($temp_url))? '' : '?'.substr($temp_url,1) ;
			$PRICE = replace_hh('HH-PM-INDEX-FILTER_PRICE1',array(
						'clear_url' => $prefix_url.$clear_url,
						'new_url' => $prefix_url.$new_url
					));
		}
		else if (BRequest::getVar('p') == 'l') 
		{
			$temp_url = preg_replace('/&p=l/i','',$append_url);
			$new_url = (empty($temp_url))? '?p=h' : '?p=h'.$temp_url ;
			$clear_url = (empty($temp_url))? '' : '?'.substr($temp_url,1) ;
			$PRICE = replace_hh('HH-PM-INDEX-FILTER_PRICE2',array(
						'clear_url' => $prefix_url.$clear_url,
						'new_url' => $prefix_url.$new_url
					));
		}
		else
		{
			$h_url = (is_null($append_url))? '?p=h' : '?p=h'.$append_url;
			$l_url = (is_null($append_url))? '?p=l' : '?p=l'.$append_url;
			$PRICE = replace_hh('HH-PM-INDEX-FILTER_PRICE3',array(
						'h_url' => $prefix_url.$h_url,
						'l_url' => $prefix_url.$l_url
					));
		}
	
		$headertext = (BRequest::getVar('category',false))? ucfirst(BRequest::getVar('category')):'today&#39;s featured';

		$return = replace_output(file_get_contents($path_site.'inc/cb/indexFilters.inc'),array('path_site'=>SITE_BASEURL,'SORT'=>$SORT,'RELEASE'=>$RELEASE,'PRICE'=> $PRICE,'header'=>$headertext));
		
		
		return $return;
	}
	// get services - featured
	function get_services($nrpp) 
	{
		// START INIT VARS \\
		global $path_site;
		$return = NULL;
		$append_url = NULL;
		$list = NULL;
		$page = (BRequest::getVar('spage') and preg_match('/^[0-9]{1,999}$/i',BRequest::getVar('spage')))? BRequest::getInt('spage') : 1 ;

		// $_REQUEST['s'] = sort and value=r is ratings, value=l is likes
		if (BRequest::getVar('s') == 'r') { $SORT = ", avg(r.`rating`) desc"; $append_url .= '&s=r';}
		else if (BRequest::getVar('s') == 'l') { $SORT = ", sum(l.`like`) desc"; $append_url .= '&s=l'; }
		else{ $SORT = NULL; }
		// $_REQUEST['r'] = release and value=n is latest to oldest released, value=o is oldest to newest
		if (BRequest::getVar('r') == 'n') { $RELEASE = ",b.dateCreated desc"; $append_url .= '&r=n';}
		else if (BRequest::getVar('r') == 'o') { $RELEASE = ",b.dateCreated asc"; $append_url .= '&r=o'; }
		else { $RELEASE=NULL; }
		// RANDOM TRIGGER IF NO SORTING SET
		$RAND = NULL;
		if(empty($SORT) && empty($RELEASE)) { $RAND='RAND()'; } 
		else { 
			if(!empty($SORT)) { $SORT = substr($SORT,1); }
			else if(!empty($RELEASE)) { $RELEASE = substr($RELEASE,1); }
		}

		$startof = (($page * $nrpp)-$nrpp)+1;
		$endof = ($page * $nrpp);
		
		$append_url = (isset($_SESSION['category']))? $append_url. '&category='.urlencode($_SESSION['category']) : $append_url ;
		$append_url = (isset($_SESSION['collection']))? $append_url. '&collection='.urlencode($_SESSION['collection']) : $append_url ;
		$append_url = (isset($_SESSION['asssortment']))? $append_url. '&asssortment='.urlencode($_SESSION['asssortment']) : $append_url ;
		$append_url = (BRequest::getVar('ppage',false))? $append_url. '&ppage='.BRequest::getVar('ppage') : $append_url ;
		// END INIT VARS \\
		
		// GO
		if(isset($_SESSION['assortment']))
		{
			$catid = 108;
			$colid = get_colid_from_name(html_entity_decode(urldecode($_SESSION['collection'])));
			$asstid = get_asstid_from_name(html_entity_decode(urldecode($_SESSION['assortment'])));
			$cacid = get_cacid($catid,$colid);
			$ccaid = get_ccaid($cacid,$asstid);
			
			$query = "select b.id as id,b.name as name,b.flag_SS as type,b.urlPM as url,b.desc as `desc`, i.filepath as fp, i.file as f, avg(r.`rating`) as rating from rel_serv_cca rscca
						join business b on rscca.bizid=b.id
						left join images i on b.id = i.itemid
						left join likes l on b.id=l.itemid
						left join ratings r on b.id=r.itemid
							where i.item='businessicon' and b.status='active' and b.flag_feat='1' and rscca.catid='108' and rscca.cacid='" .$cacid. "' and rscca.ccaid='" .$ccaid. "'
								group by b.id
								order by ".$RAND.$SORT.$RELEASE;
		}
		else if(isset($_SESSION['collection']))
		{
			$catid = 108;
			$colid = get_colid_from_name(html_entity_decode(urldecode($_SESSION['collection'])));
			$cacid = get_cacid($catid,$colid);
			
			$query = "select b.id as id,b.name as name,b.flag_SS as type,b.urlPM as url,b.desc as `desc`, i.filepath as fp, i.file as f, avg(r.`rating`) as rating from rel_serv_cca rscca
						join business b on rscca.bizid=b.id
						left join images i on b.id = i.itemid
						left join likes l on b.id=l.itemid
						left join ratings r on b.id=r.itemid
							where i.item='businessicon' and b.status='active' and b.flag_feat='1' and rscca.catid='108' and rscca.cacid='" .$cacid. "'
								group by b.id
								order by ".$RAND.$SORT.$RELEASE;
		}
		else
		{
			$query = "select b.id as id,b.name as name,b.flag_SS as type,b.urlPM as url,b.desc as `desc`, i.filepath as fp, i.file as f, avg(r.`rating`) as rating from rel_serv_cca rscca
						join business b on rscca.bizid=b.id
						left join images i on b.id = i.itemid
						left join likes l on b.id=l.itemid
						left join ratings r on b.id=r.itemid
							where i.item='businessicon' and b.status='active' and b.flag_feat='1' and rscca.catid='108'
								group by b.id
								order by ".$RAND.$SORT.$RELEASE;
		}
		//CACHING SEARCH RESULTS FOR THE LAST 30 MINUTES OF SESSION
		if(!isset($_SESSION['indexServData'][$query]) || $_SESSION['indexServData'][$query]['time'] < (time() - (30*60)))
		{
			$result = mysql_query($query) or die(mysql_error());
			$_SESSION['indexServData'][$query]['time'] = time();
			$_SESSION['indexServData'][$query]['count'] = mysql_num_rows($result);
			$i=0;
			if( $_SESSION['indexServData'][$query]['count'] > 0 )
			{
				while($row = mysql_fetch_assoc($result))
				{
					$_SESSION['indexServData'][$query]['data'][$i] = $row;	
					$i++;
				}
			}
			else
			{
				$_SESSION['indexServData'][$query]['data'] = NULL;
			}
		}
		
		$data = $_SESSION['indexServData'][$query]['data'];
		if(!empty($data))
		{
			$count_total = $_SESSION['indexServData'][$query]['count'];
			
			$num_pages = ceil($count_total / $nrpp);
			$prev = ($page > 1)? '<a href="' .SITE_BASEURL.'index.php?spage=' .($page-1).$append_url. '">&laquo; previous</a>' : NULL ;		
			$next = ($page < $num_pages)? '<a href="' .SITE_BASEURL.'index.php?spage='.($page+1).$append_url. '">next &raquo;</a>' : NULL ;
			$pn_divide = (!empty($prev) and !empty($next))? ' | ' : NULL ;
			
			if($page > 1) { $y = (($page -1) * $nrpp); $z = ($page * $nrpp); }
			else { $y = 0; $z = $nrpp; }

			while($y < $z)
			{
				if(isset($data[$y]['name']))
				{
					$seoPhrase = preg_replace('/[[:punct:]]*/i','',stripslashes($data[$y]['name']));
					$seoPhrase = preg_replace('/[[:space:]]+/i','-',$seoPhrase);
					$seoPhrase = preg_replace('/-{0,1}$/i','',$seoPhrase);
					$margin = (!(1&$y))? '6px' : 0 ;
					
					$rating  = round($data[$y]['rating'], 0);
					$ratings = compile_rating($data[$y]['id'], $rating);
					
					$list .= replace_hh('HH-PM-INDEX-SERV1', array(
						'path_site' => SITE_BASEURL,
						'alt' => stripslashes($data[$y]['name']),
						'title' => truncate_hard(stripslashes($data[$y]['name']),26),
						'desc' => truncate(strip_tags(stripslashes($data[$y]['desc'])),42),
						'image_src' => SITE_BASEURL.$data[$y]['fp'].$data[$y]['f'],
						'margin' => $margin,
						'rating' => $ratings,	
						'serv_href' => SITE_BASEURL.$data[$y]['url'].'/'.$seoPhrase
					));
				}
					$y++;
			}
			
			$header_text = (BRequest::getVar('category', false, 'session') == 'services')? 'featured services' : 'services' ; 
			$endof = ($count_total < $endof)? $count_total : $endof ;	
			$return = replace_output(file_get_contents($path_site.'inc/cb/indexServices.inc'), array(
					'start' => $startof,
					'end' => $endof,
					'headertxt' => $header_text,
					'total' => $count_total,
					'prevnext'=> $prev.$pn_divide.$next,
					'services' => $list			
				));
		}
		else
		{
			$return = '<div class="services_box" style="width:320px; padding:20px;">'.ERROR_MS_SERV1.'</div>';
		}		
		
		return $return;
	}
	// get services in main category not featured
	function get_services_main($nrpp) 
	{
		// START INIT VARS \\
		global $path_site;
		$return = NULL;
		$append_url = NULL;
		$list = NULL;
		$page = (BRequest::getVar('smpage',false) and preg_match('/^[0-9]{1,999}$/i',BRequest::getVar('smpage')))? BRequest::getVar('smpage') : 1 ;

		// $_REQUEST['s'] = sort and value=r is ratings, value=l is likes
		if(BRequest::getVar('s') == 'r') { $SORT = ", avg(r.`rating`) desc"; $append_url .= '&s=r';}
		else if (BRequest::getVar('s') == 'l') { $SORT = ", sum(l.`like`) desc"; $append_url .= '&s=l'; }
		else{ $SORT = NULL; }
		// $_REQUEST['r'] = release and value=n is latest to oldest released, value=o is oldest to newest
		if(BRequest::getVar('r') == 'n') { $RELEASE = ",b.dateCreated desc"; $append_url .= '&r=n';}
		else if (BRequest::getVar('r') == 'o') { $RELEASE = ",b.dateCreated asc"; $append_url .= '&r=o'; }
		else { $RELEASE=NULL; }
		// RANDOM TRIGGER IF NO SORTING SET
		$RAND = NULL;
		if(empty($SORT) && empty($RELEASE)) { $RAND='RAND()'; } 
		else { 
			if(!empty($SORT)) { $SORT = substr($SORT,1); }
			else if(!empty($RELEASE)) { $RELEASE = substr($RELEASE,1); }
		}

		$startof = (($page * $nrpp)-$nrpp)+1;
		$endof = ($page * $nrpp);
		
		$append_url = (isset($_SESSION['category']))? $append_url. '&category='.urlencode($_SESSION['category']) : $append_url ;
		$append_url = (isset($_SESSION['collection']))? $append_url. '&collection='.urlencode($_SESSION['collection']) : $append_url ;
		$append_url = (isset($_SESSION['asssortment']))? $append_url. '&asssortment='.urlencode($_SESSION['asssortment']) : $append_url ;
		$append_url = (BRequest::getVar('spage',false))? $append_url. '&spage='.BRequest::getVar('spage') : $append_url ;
		// END INIT VARS \\
		
		// GO
		if(isset($_SESSION['assortment']))
		{
			$catid = 108;
			$colid = get_colid_from_name(html_entity_decode(urldecode($_SESSION['collection'])));
			$asstid = get_asstid_from_name(html_entity_decode(urldecode($_SESSION['assortment'])));
			$cacid = get_cacid($catid,$colid);
			$ccaid = get_ccaid($cacid,$asstid);
			
			$query = "select b.id as id,b.name as name,b.flag_SS as type,b.urlPM as url,b.desc as `desc`, i.filepath as fp, i.file as f, avg(r.`rating`) as rating from rel_serv_cca rscca
						join business b on rscca.bizid=b.id
						left join images i on b.id = i.itemid
						left join likes l on b.id=l.itemid
						left join ratings r on b.id=r.itemid
							where i.item='businessicon' and b.status='active' and rscca.catid='108' and rscca.cacid='" .$cacid. "' and rscca.ccaid='" .$ccaid. "'
								group by b.id
								order by ".$RAND.$SORT.$RELEASE;
		}
		else if(isset($_SESSION['collection']))
		{
			$catid = 108;
			$colid = get_colid_from_name(html_entity_decode(urldecode($_SESSION['collection'])));
			$cacid = get_cacid($catid,$colid);
			
			$query = "select b.id as id,b.name as name,b.flag_SS as type,b.urlPM as url,b.desc as `desc`, i.filepath as fp, i.file as f, avg(r.`rating`) as rating from rel_serv_cca rscca
						join business b on rscca.bizid=b.id
						left join images i on b.id = i.itemid
						left join likes l on b.id=l.itemid
						left join ratings r on b.id=r.itemid
							where i.item='businessicon' and b.status='active' and rscca.catid='108' and rscca.cacid='" .$cacid. "'
								group by b.id
								order by ".$RAND.$SORT.$RELEASE;
		}
		else
		{
			$query = "select b.id as id,b.name as name,b.flag_SS as type,b.urlPM as url,b.desc as `desc`, i.filepath as fp, i.file as f, avg(r.`rating`) as rating from rel_serv_cca rscca
						join business b on rscca.bizid=b.id
						left join images i on b.id = i.itemid
						left join likes l on b.id=l.itemid
						left join ratings r on b.id=r.itemid
							where i.item='businessicon' and b.status='active' and rscca.catid='108'
								group by b.id
								order by ".$RAND.$SORT.$RELEASE;
		}
		//CACHING SEARCH RESULTS FOR THE LAST 30 MINUTES OF SESSION
		if(!isset($_SESSION['indexServmainData'][$query]) || $_SESSION['indexServmainData'][$query]['time'] < (time() - (30*60)))
		{
			$result = mysql_query($query) or die(mysql_error());
			$_SESSION['indexServmainData'][$query]['time'] = time();
			$_SESSION['indexServmainData'][$query]['count'] = mysql_num_rows($result);
			$i=0;
			if( $_SESSION['indexServmainData'][$query]['count'] > 0 )
			{
				while($row = mysql_fetch_assoc($result))
				{
					$_SESSION['indexServmainData'][$query]['data'][$i] = $row;	
					$i++;
				}
			}
			else
			{
				$_SESSION['indexServmainData'][$query]['data'] = NULL;
			}
		}
		
		$data = $_SESSION['indexServmainData'][$query]['data'];
		if(!empty($data))
		{
			$count_total = $_SESSION['indexServmainData'][$query]['count'];
			
			$num_pages = ceil($count_total / $nrpp);
			$prev = ($page > 1)? '<a href="' .SITE_BASEURL.'index.php?spage=' .($page-1).$append_url. '">&laquo; previous</a>' : NULL ;		
			$next = ($page < $num_pages)? '<a href="' .SITE_BASEURL.'index.php?spage='.($page+1).$append_url. '">next &raquo;</a>' : NULL ;
			$pn_divide = (!empty($prev) and !empty($next))? ' | ' : NULL ;
			
			if($page > 1) { $y = (($page -1) * $nrpp); $z = ($page * $nrpp); }
			else { $y = 0; $z = $nrpp; }

			while($y < $z)
			{
				if(isset($data[$y]['name']))
				{
					$seoPhrase = preg_replace('/[[:punct:]]*/i','',stripslashes($data[$y]['name']));
					$seoPhrase = preg_replace('/[[:space:]]+/i','-',$seoPhrase);
					$seoPhrase = preg_replace('/-{0,1}$/i','',$seoPhrase);
					
					$rating  = round($data[$y]['rating'], 0);
					$ratings = compile_rating($data[$y]['id'], $rating);
					
					$list .= replace_hh('HH-PM-INDEX-SERV2', array(
						'path_site' => SITE_BASEURL,
						'alt' => stripslashes($data[$y]['name']),
						'title' => truncate_hard(stripslashes($data[$y]['name']),30),
						'desc' => truncate(strip_tags(stripslashes($data[$y]['desc'])),100),
						'image_src' => SITE_BASEURL.$data[$y]['fp'].$data[$y]['f'],
						'serv_href' => SITE_BASEURL.$data[$y]['url'].'/'.$seoPhrase
					));
				}
					$y++;
			}
			$endof = ($count_total < $endof)? $count_total : $endof ;	

			$return = replace_output(file_get_contents($path_site.'inc/cb/indexServicesMain.inc'), array(
					'start' => $startof,
					'end' => $endof,
					'total' => $count_total,
					'prevnext'=> $prev.$pn_divide.$next,
					'services' => $list			
				));
		}
		else
		{
			$return = '<div class="services_box" style="width:440px; padding:20px;">'.ERROR_MS_SERV1.'</div>';
		}		
		
		return $return;
	}
	
	// get content
	function get_content()
	{
		// START INIT VARS \\
		$return = NULL;
		$prod_nrpp = 18; // number of product results per page
		$serv_nrpp = 26; // number of product results per page
		$serv_main_nrpp = 30; // number of product results per page
		
		if(BRequest::getVar('category') == 'services') { $return = $this->get_filters().$this->get_services_main($serv_main_nrpp).$this->get_services($serv_nrpp); }
		else { $return = $this->get_filters().$this->get_products($prod_nrpp).$this->get_services($serv_nrpp); }

		return $return;
	}

	// get biz listing
	function get_biz() 
	{
		// INIT VARS \\
		global $path_site;
		$return = NULL;
		$LIMIT = " LIMIT 9";
		
		// GET TOTAL FEATURED COUNT AND PUT IN ARRAY
		$time = time();
		$query = "select b.urlPM as name,b.name as title, b.desc as `desc`, i.filepath as fp, i.file as f from business b 
					left join images i on b.id = i.itemid
						where i.item='businessicon' and b.flag_feat='1' and b.feat_start < '" .$time. "' and b.feat_end > '" .$time. "' and b.`status`='active' and b.dateExpires > '" .$time. "'
							order by RAND()".$LIMIT;
		$result = mysql_query($query) or die(mysql_error());
		$count = mysql_num_rows($result);
		if($count > 0)
		{
			while($row = mysql_fetch_assoc($result))
			{
				$seoPhrase = preg_replace('/[[:punct:]]*/i','',stripslashes($row['desc']));
				$seoPhrase = preg_replace('/[[:space:]]+/i','-',$seoPhrase);
				$return .= replace_output(
					HH_PM_FM1,
					array(
						'name' => stripslashes($row['title']),
						'description' => stripslashes($row['desc']),
						'p_link' => SITE_BASEURL.$row['name'].'/'.$seoPhrase,
						//'p_link' => SITE_BASEURL.'business.php?name='.$row['name'],
						'src' => SITE_BASEURL.$row['fp'].$row['f']
					)
				);
			}
		}
		else
		{
			$return = ERROR_MS_BIZ1;
		}

		$return = replace_output(file_get_contents($path_site.'inc/cb/favorite_business_box.inc'), array('path_site' => SITE_BASEURL,'business_list' => $return) );
		return $return;
	}
	

}



?>