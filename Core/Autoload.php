<?php
/**
 * 
 */

namespace CLx\Core;
use Exception;

class Autoload {
	
	private function __construct() {}
	
	public static function CLxLoad($class_name) {
		$class_name = str_replace('\\', '/', $class_name);
		$class_name = preg_replace('/^'.CLX_SYS_PREFIX.'\//', CLX_SYS_ROOT, $class_name);
		
		if(!file_exists($class_name . '.php'))
			throw new Exception("Class is not exists.");
		
		require_once $class_name . '.php'; 
	}

	public static function Register() {
		spl_autoload_register('self::CLxLoad');
	}
}
