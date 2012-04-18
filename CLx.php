<?php

/**
 * CLx
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link		http://github.com/scarwu/CLx
 * @since		Version 1.0
 * @filesource
 */

if('development' === CLX_MODE || 'test' === CLX_MODE) {
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}
else
	error_reporting(0);

define('SEPARATOR', DIRECTORY_SEPARATOR);

define('CLX_SYS_CORE', CLX_SYS_ROOT . 'Core' . SEPARATOR);
define('CLX_SYS_LIBRARY', CLX_SYS_ROOT . 'Library' . SEPARATOR);

define('CLX_APP_CACHE', CLX_APP_ROOT . 'Cache' . SEPARATOR);
define('CLX_APP_CONFIG', CLX_APP_ROOT . 'Config' . SEPARATOR);
define('CLX_APP_CONTROLLERS', CLX_APP_ROOT . 'Controllers' . SEPARATOR);
define('CLX_APP_LIBRARY', CLX_APP_ROOT . 'Library' . SEPARATOR);
define('CLX_APP_LOGS', CLX_APP_ROOT . 'Logs' . SEPARATOR);
define('CLX_APP_MODELS', CLX_APP_ROOT . 'Models' . SEPARATOR);
define('CLX_APP_VIEWS', CLX_APP_ROOT . 'Views' . SEPARATOR);

require_once CLX_SYS_ROOT . 'Config.php';
require_once CLX_SYS_ROOT . 'Core/Autoload.php';
require_once CLX_SYS_ROOT . 'Core/Router.php';

require_once CLX_APP_CONFIG . 'Route.php';

$OWRouter = new Router();

foreach((array)$Route as $rule)
	$OWRouter->add($rule[0], $rule[1]);

$OWRouter->run();
