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

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('ABSPATH') or define('ABSPATH', dirname(dirname(__file__)).DS);

//loading necessary dependancies
require_once dirname(__file__).DS."defines.php";
require_once INCLUDES."configurations.php";

//loading configuration settings
$five_config = fiveConfigurations();

/**
 * INI Settings
 * 
 * All of the ini settings and their values are set in the xml file. This is 
 * to ensure that we can edit them in the future without changing php.
 */
foreach ((array)$five_config->ini as $ini => $value)
	ini_set($ini, $value);

/**
 * Default Timezone
 * 
 * This is a major complaint of php if its not stated early on.
 */
date_default_timezone_set($five_config->base->date_default_timezone_set);

//loading necessary files
require_once INCLUDES."actions.php";
require_once INCLUDES."debug.php";
require_once INCLUDES."object.php";
require_once INCLUDES."request.php";
require_once INCLUDES."templates.php";
require_once INCLUDES."controller.php";
require_once INCLUDES."plugins.php";
require_once INCLUDES."user.php";
require_once INCLUDES."functions.php";
require_once INCLUDES."path.php";
require_once INCLUDES."factory.php";
require_once INCLUDES."application.php";
require_once INCLUDES."router.php";
require_once INCLUDES."set.php";
require_once INCLUDES."string.php";
require_once INCLUDES."files.php";
require_once INCLUDES."compression.php";
require_once INCLUDES."database".DS."database.php";
require_once INCLUDES."database".DS."table.php";

/**
 * Initializing the system
 * 
 * This is generally for internal calls only.
 */
do_action('system');

/**
 * Loading the theme functions
 * 
 * Making sure that we load the themes functions file as soon as possible.
 */
if (file_exists($functions = get_theme_dir().'functions.php'))
	require_once $functions;

/**
 * Stylesheets
 * 
 * default system styles
 */
register_stylesheet('five-includes/views/style.css');


