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

//redirect if successful
if(!is_user_logged_in()) { redirect( Router::url(array('controller'=>'user','action'=>'login')) ); }

$return = NULL;
$query = "select o.id as order_id,o.bizids as bizids, o.date_ordered as 'date', i.id as inv_id, o.amt_total as total from `orders` o
			left join `invoices` i on o.id = i.orderid
				where o.userid = '" .get_current_user_id(). "' and ( o.status='complete' or o.status='ship' ) group by o.id order by o.date_ordered asc";
$result = mysql_query($query);

if(mysql_num_rows($result) > 0)
{
	while($row = mysql_fetch_assoc($result))
	{
		$bizids = explode(',',$row['bizids']);
		$biznames = NULL;
		foreach($bizids as $b)
		{
			$query 		= "select name from business where id='" .$b. "'";
			$result1 	= mysql_query($query) or die(mysql_error());
			$row1 		= mysql_fetch_row($result1);
			$biznames 	.= stripslashes($row1[0]).',';
		}
		$date = date('F jS Y',$row['date']);
		//$return .= replace_output(HH_PM_PROF_ORDERHIS, array('order'=>$row['order_id'],'name'=>substr($biznames,0,-1),'date'=>$date,'inv'=>$row['inv_id'],'amt'=>$row['total'] ));
		
		$params = array(
			'order' 	=> $row['order_id'],
			'name' 		=> substr($biznames,0,-1),
			'date' 		=> $date,
			'inv' 		=> $row['inv_id'],
			'amt' 		=> $row['total'] 
		);
		extract($params);
		require $view;
	}
}
else
{
	?>
	<li><div class="site_errors">You have not placed any orders with us.</div></li>
	<?php 
}

