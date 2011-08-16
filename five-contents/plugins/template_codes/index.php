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

//loading library
require_once dirname(__file__).DS."functions.php";

//site urls
add_filter('template_code_http_theme', 'pms_http_theme');

add_filter('template_code_baseurl', 'pms_path_site');
add_filter('template_code_site_url', 'pms_path_site');
add_filter('template_code_logo_href', 'pms_path_site');
add_filter('template_code_sbu', 'pms_path_site');
add_filter('template_code_sp', 'pms_path_site');
add_filter('template_code_path_site', 'pms_path_site');

add_filter('template_code_body_class', 'pms_body_class');
add_filter('template_code_site_title', 'pms_site_title');

add_filter('template_code_rss', 'pms_rss_feed');
add_filter('template_code_site_url', 'pms_site_url');
add_filter('template_code_site_baseurl', 'pms_path_site');

add_filter('template_code_ga', 'pms_google_analytics');
add_filter('template_code_qc', 'pms_gc_analytics');
add_filter('template_code_rss', 'pms_rss_feed');

add_filter('template_code_site_name', 'pms_site_name');

//login
add_filter('template_code_return_msg', 'pms_return_msg');
add_filter('template_code_captcha', 'pms_captcha');
add_filter('template_code_logo_href', 'pms_logo_href');

/*
//main page
add_filter('template_code_cart_count', 'pms_cart_count');
add_filter('template_code_nav_links', 'pms_nav_links');
add_filter('template_code_join', 'pms_join');
add_filter('template_code_profile', 'pms_profile');
add_filter('template_code_account', 'pms_account');
add_filter('template_code_signin', 'pms_signin');
add_filter('template_code_signout', 'pms_signout');

add_filter('template_code_side_nav', 'pms_side_nav');
add_filter('template_code_collections', 'pms_collections');
add_filter('template_code_assortments', 'pms_assortments');
add_filter('template_code_sm_icons_index', 'pms_sm_icons_index');
add_filter('template_code_news_events', 'pms_news_events');
add_filter('template_code_this_month', 'pms_this_month');
add_filter('template_code_advertise_banner', 'pms_advertise_banner');
add_filter('template_code_think_global_shop_local', 'pms_think_global_shop_local');
add_filter('template_code_pm_bill_of_rights', 'pms_pm_bill_of_rights');
add_filter('template_code_our_favorite_things', 'pms_our_favorite_things');
add_filter('template_code_category_calendar', 'pms_category_calendar');
add_filter('template_code_banner', 'pms_banner');

add_filter('template_code_data_un', 'pms_data_un');
 
add_filter('template_code_header_style', 'pms_header_style');
add_filter('template_code_list', 'pms_list');
*/

