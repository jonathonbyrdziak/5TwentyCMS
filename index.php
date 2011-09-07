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

// loads the Five Twenty CMS
require_once dirname(__file__).'/five-includes/bootstrap.php';

/**
 * Initializing
 * 
 * Now that everything is loaded and ready, you can initialize
 */
do_action('init');

/**
 * Application
 * 
 * Attached to this action is the functionality that will determine
 * what page that we will be looking at.
 */
do_action('application');

/**
 * Shutting Down
 * 
 * Just in case you want to do any debugging or other cleanup. This
 * action is available to you.
 */
do_action('shutdown');
