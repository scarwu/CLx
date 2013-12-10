<?php
/**
 * CLx Bootstrap
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

/**
 * Switch Mode
 */
if('development' === CLX_MODE || 'test' === CLX_MODE) {
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}
else
	error_reporting(0);

/**
 * Require CLx Config
 */
require_once CLX_SYS_ROOT . 'Config.php';

/**
 * Require CLx Autoload
 */
require_once CLX_SYS_ROOT . 'Core/Autoload.php';

// Register Autoload
\CLx\Core\Autoload::register();
 
/**
 * Require Route Config, Setting Router and Run
 */

// Init Router
$router = new \CLx\Core\Router($_SERVER['REQUEST_METHOD'], \CLx\Core\Request::uri());

// Add Route List
$router->addList(\CLx\Core\Loader::config('Route'));

// Run Router
$router->run();
