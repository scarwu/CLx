<?php
/**
 * CLx Autoloader
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

namespace CLx\Core;
use Exception;

class Autoload {
	
	private function __construct() {}
	
	public static function load($class_name) {
		$class_name = str_replace('\\', '/', $class_name);
		$class_name = preg_replace('/^'.CLX_SYS_PREFIX.'\//', CLX_SYS_ROOT, $class_name);
		
		if(!file_exists($class_name . '.php'))
			throw new Exception("Class is not exists.");
		
		require_once $class_name . '.php'; 
	}

	public static function register() {
		spl_autoload_register('self::load');
	}
}
