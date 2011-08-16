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

//declaring definitions
define('ADMIN', ABSPATH."five-admin".DS);
define('CONTENTS', ABSPATH."five-contents".DS);
	define('THEMES', CONTENTS."themes".DS);
	define('PLUGINS', CONTENTS."plugins".DS);
	define('UPLOADS', CONTENTS."uploads".DS);
		define('AVATARS', UPLOADS."avatar".DS);
		define('PRODUCTS', UPLOADS."product".DS);

define('INCLUDES', ABSPATH."five-includes".DS);
define('ABSADMIN', ABSPATH."admin".DS);
