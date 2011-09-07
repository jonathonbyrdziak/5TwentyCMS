<?php 
/**
 * @Author	Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Five Twenty CMS
 * @SubPackage PublicMarketSpace
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Setting routes
 * 
 * The routes are url matches that aid the program in locating functions
 */
Router::connect('/register', array('controller' => 'user', 'action' => 'register'));
Router::connect('/login', array('controller' => 'user', 'action' => 'login'));
Router::connect('/logout', array('controller' => 'user', 'action' => 'logout'));
Router::connect('/search', array('controller' => 'search', 'action' => ''));

Router::connect( 
	'/categories/:category/:collection/:assortments',
	array( 'controller' => 'categories' ),
	array( 'pass' => array('category','collection','assortments'))
);

Router::connect( 
	'/categories/:category/:collection',
	array( 'controller' => 'categories' ),
	array( 'pass' => array('category','collection') )
);

Router::connect( 
    '/categories/:category',
    array( 'controller' => 'categories' ),
    array( 'pass' => array('category') )
);

Router::connect( 
	'/business/:bizid/:slug',
	array( 'controller' => 'business'),
	array( 'pass' => array('bizid','slug') )
);

//breaking down the current url
$url = Router::parse();
if (isset($url['controller']) && $url['controller'] == 'user')
{
	if ($url['action'] == 'products')
	{
		add_action('init', 'user_products_head');
	}
	elseif ($url['action'] == 'service')
	{
		add_action('init', 'user_products_head');
	}
}

function user_products_head()
{
	register_script( url('js/jquery/jquery.noblecount.js') );
	register_script( url('js/jquery/jquery.profile.custom.js') );
}

function pms_set_like()
{
	if(!is_user_logged_in()) { return '2'; }
	
	$userid = get_current_user_id();
	$item = BRequest::getVar('method');
	$itemid = BRequest::getVar('i');
	$date = time();
	
	$result = mysql_query("select id from `likes` where `userid`='" .$userid. "' and item='" .$item. "' and itemid='" .$itemid. "'");
	
	if(mysql_num_rows($result) < 1){ 
		$first = "insert into "; 
		$last = ''; 
	} 
	else { 
		$first = "update "; 
		$last = " where itemid='" .$itemid. "' and item='" .$item. "' and userid='" .$userid. "'";  
	}
	
	$query = $first."`likes` set
			`userid` = '" .$userid. "',
			`item`='" .$item. "',
			`itemid`='" .$itemid. "',
			`like`='1',
			`date_added` = '" .$date. "'
			".$last;
	if(mysql_query($query)) { 
		return '1'; 
	} 
	else { 
		return '0'; 
	}
}

function pms_set_rating()
{
	if(!is_user_logged_in() || !isset($_REQUEST['rating'])) { 
		return '2'; 
	}
	
	$userid = get_current_user_id();
	$item = BRequest::getVar('method');
	$itemid = BRequest::getVar('i');
	$date = time();
	
	$update = "update ratings set rating='" 
		.BRequest::getVar('rating'). "',date_added='" 
		.$date. "' where item='" .$item. "' and itemid='" 
		.$itemid. "' and userid='" .$userid. "'";
		
	$result = mysql_query($update) or die(mysql_error());
	
	if(mysql_affected_rows() == 0)
	{
		$query = "insert into ratings set rating='" 
			.BRequest::getVar('rating'). "', item='" 
			.$item. "', itemid='" .$itemid. "', userid='" 
			.$userid. "', date_added='" .$date. "'";
			
		if(mysql_query($query)) { 
			return '1'; 
		} 
		else { 
			return '0'; 
		}
	}
	else { 
		return '1'; 
	}
	
}