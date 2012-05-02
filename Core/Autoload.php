<?php

namespace CLx\Core;
use Exception;

class Autoload {
	
	private function __construct() {}
	
	public static function CLxLoad($class_name) {
		$class_name = str_replace('\\', '/', $class_name);
		
		if(preg_match('/^'.CLX_SYS_PREFIX.'\//', $class_name))
			$class_name = preg_replace('/^'.CLX_SYS_PREFIX.'\//', CLX_SYS_ROOT, $class_name);
		elseif(preg_match('/^'.CLX_APP_PREFIX.'\//', $class_name))
			$class_name = preg_replace('/^'.CLX_APP_PREFIX.'\//', CLX_APP_ROOT, $class_name);
		
		if(file_exists($class_name . '.php'))
			require_once $class_name . '.php';
		else 
			throw new Exception("Class is not exsist.");
	}

	public static function Register() {
		spl_autoload_register('self::CLxLoad');
	}
}
