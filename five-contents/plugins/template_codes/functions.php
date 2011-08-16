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

function pms_site_name()
{
	return 'Portland Saturday Market';
}

function pms_http_theme()
{
	$five_config = fiveConfigurations();
	return url("five-contents/themes/{$five_config->theme->name}/");
}

//pms_list
function pms_list()
{
	return HH_PM_DHDR_LOGIN_NAV1;
}

//pms_header_style
function pms_header_style()
{
	return 'banner';
}

//pms_return_msg
function pms_rss_feed()
{
	return RSS_FEED;
}

//pms_return_msg
function pms_gc_analytics()
{
	return QC_ANALYTICS;
}

//pms_return_msg
function pms_google_analytics()
{
	return GOOGLE_ANALYTICS;
}

//pms_return_msg
function pms_return_msg( $content = null )
{
	return BRequest::getVar('returnmsg', $content);
}

//pms_captcha
function pms_captcha()
{
	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;
	
	require_once ABSPATH.DS."bin".DS."recaptcha-php-1.10".DS."recaptchalib.php";
	
	return ' '.recaptcha_get_html(CX_CAPTCHA_PUBLIC_KEY, $error,TRUE);
}

//pms_data_un
function pms_data_un()
{
	return BRequest::getVar('username', 'Guest');
}

//pms_site_title
function pms_site_title()
{
	//loading configuration settings
	$five_config = fiveConfigurations();
	return $five_config->base->site_title;
}

//pms_sm_icons_index
function pms_sm_icons_index()
{
	$cl = new contentLogic();
	return $cl->get_smicons('index');
}

//pms_sm_icons_index
function pms_news_events()
{
	$cl = new contentLogic();
	return $cl->get_boxTwo('news');
}

//pms_sm_icons_index
function pms_this_month()
{
	return '';
}

//pms_sm_icons_index
function pms_advertise_banner()
{
	return rand(1, 2);
}

//pms_sm_icons_index
function pms_think_global_shop_local()
{
	$cl = new contentLogic();
	return $cl->get_boxSeven('tgsl');
}

//pms_sm_icons_index
function pms_pm_bill_of_rights()
{
	$cl = new contentLogic();
	return $cl->get_boxSeven('bor');
}

//pms_sm_icons_index
function pms_our_favorite_things()
{
	$cl = new contentLogic();
	//return $cl->get_boxSeven('oft');
}

//pms_sm_icons_index
function pms_category_calendar()
{
	$cl = new contentLogic();
	return $cl->get_boxSeven('cm');
}

//pms_sm_icons_index
function pms_banner()
{
	$cl = new contentLogic();
	return $cl->get_banner();
}

//widget assortments
function pms_assortments()
{
	$cl = new contentLogic();
	return $cl->get_boxOne('assortments');
}

//widget collections
function pms_collections()
{
	$cl = new contentLogic();
	return $cl->get_boxOne('collections');
}

//Side navigation
function pms_side_nav()
{
	$cl = new contentLogic();
	return $cl->get_sideNav();
}

//must return the header template
function pms_header()
{
	return get_show_view('header');
}

//returns the site base url
function pms_path_site()
{
	//loading configuration settings
	$five_config = fiveConfigurations();
	return $five_config->base->url;
}

//returns the meta to be displayed in the page header
function pms_meta()
{
	global $meta_desc, $meta_keywords, $title;
	require_once ABSPATH.'/inc/keywords.php'; // KEYWORD ARRAY
	$keywords = get_keywords();
	
	// START META AND TITLE INFO FOR PAGE
	$meta_desc = '<meta name="description" content="' .SITE_DESC. ' - ';
	$title ='<title>';
	
	do_action('meta');
	
	// END META AND TITLE INFO FOR PAGE
	$meta_desc .= '" />'."\n";
	$meta_keywords = '<meta name="keywords" content="'. $keywords . SITE_KEYWORDS . '" />';
	$title .= SITE_TITLE.'</title>';
	$meta = $meta_desc.$meta_keywords.$title;
	
	return $meta;
}

//function will return the footer template
function pms_footer()
{
	return get_show_view('footer');
}

//returns the classes for the given page
function pms_body_class()
{
	global $page;
	return $page;
}

//count cart items
function pms_cart_count()
{
	$cl = new contentLogic();
	return $cl->get_cart_count();
}

//regular url
function pms_site_url()
{
	return url();
}

//
function pms_navlist()
{
	$cl = new contentLogic();
	return $cl->get_headernav();
}

//
function pms_nav_links()
{
	$cl = new contentLogic();
	return $cl->get_header_links();
}

//
function pms_logo_href()
{
	return SITE_BASEURL;
}

//
function pms_join()
{
	if (is_user_logged_in()) return false;
	return replace_hh('HH-PM-HDR-TEXTNAV-JOIN',array('path_site'=>SITE_BASEURL));
}

//
function pms_signin()
{
	if (is_user_logged_in()) return false;
	return replace_hh('HH-PM-HDR-TEXTNAV-JOIN',array('path_site'=>SITE_BASEURL));
}

//
function pms_signout()
{
	if (!is_user_logged_in()) return false;
	return replace_hh('HH-PM-HDR-TEXTNAV-SIGNOUT',array('path_site'=>SITE_BASEURL));
}

//
function pms_profile()
{
	if (!is_user_logged_in()) return false;
	return replace_hh('HH-PM-HDR-TEXTNAV-PROFILE',array('id'=>$_SESSION['user']['publicid'],'path_site'=>SITE_BASEURL));
}

//
function pms_account()
{
	if (!is_user_logged_in()) return false;
	return replace_hh('HH-PM-HDR-TEXTNAV-ACCOUNT',array('path_site'=>SITE_BASEURL));
}



