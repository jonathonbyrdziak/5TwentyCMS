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
 * Security Setting
 *
 * This constant is specifically checked within every file for security reaons.
 * It is not used for anything else but to prevent files from being directly
 * accessed.
 */
define('IN_MAIN', true);

/**
 * The maintenance
 * 
 * This file is included by the original developers for debugging reasons.
 */
//require('maintenance.html');exit();

/**
 * Start the Session
 * 
 * We're starting up the session here.
 * @TODO this needs more valid checks before we can start it securely.
 */
@ session_start();

/**
 * declaring globals
 */
$page = 'main'; //main is default

/**
 * Including libraries
 * 
 * We're going to require that the global libraries are included here.
 */
require_once ABSPATH.'inc'.DS.'database.php';
require_once ABSPATH.'inc'.DS.'common.php';

require_once ABSPATH.'inc'.DS.'constants.php';
require_once ABSPATH.'inc'.DS.'content_logic.php';
require_once ABSPATH.'bin'.DS.'XPM4'.DS.'MAIL.php';

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
