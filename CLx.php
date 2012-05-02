<?php
/**
 * CLx Bootstrap
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

if('development' === CLX_MODE || 'test' === CLX_MODE) {
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
}
else
	error_reporting(0);

require_once CLX_SYS_ROOT . 'Config.php';
require_once CLX_SYS_ROOT . 'Core' . DIRECTORY_SEPARATOR . 'Autoload.php';

\CLx\Core\Autoload::Register();

require_once CLX_APP_CONFIG . 'Route.php';

$CLXRouter = new \CLx\Core\Router($_SERVER['REQUEST_METHOD'], \CLx\Core\Request::Uri());

$CLXRouter->AddList($Route);

$CLXRouter->Run();
