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

define('CLX_SYS_CORE', CLX_SYS_ROOT . 'Core' . DIRECTORY_SEPARATOR);
define('CLX_SYS_LIBRARY', CLX_SYS_ROOT . 'Library' . DIRECTORY_SEPARATOR);

require_once CLX_SYS_ROOT . 'Config.php';
require_once CLX_SYS_CORE . 'Autoload.php';
require_once CLX_SYS_CORE . 'Request.php';
require_once CLX_SYS_CORE . 'Router.php';

$OWRouter = new Router();

require_once CLX_APP_CONFIG . 'Route.php';
foreach((array)$Route as $rule)
	$OWRouter->Add($rule[0], $rule[1]);

$OWRouter->Run();
