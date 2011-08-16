<?php 
/**
 * @Author Jonathon byrd
 * @link http://www.5twentystudios.com
 * @Package Importing
 * @Since 1.0.0
 * @copyright  Copyright (C) 2011 5Twenty Studios
 * 
 */

defined('ABSPATH') or _die("Cannot access pages directly.");

/**
 * Template Management
 * 
 * Declaring this folder as a folder that template files can be
 * located within.
 */
add_view_path( dirname(__file__) );

/**
 * Pemalink Management
 * 
 * Defining exactly what 
 */
Router::connect('/import', array('controller' => 'import', 'action' => 'index', 'plugin' => 'import'));

/**
 * Stylesheets
 */
register_stylesheet( plugin_url(__file__,'css/import.css') );



