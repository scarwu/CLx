<?php

// namespace CLx\Core;

class Autoload {
	
	private function __construct() {}
	
	public static function SysLoad($class_name) {
		$class_name = str_replace('\\', '/', $class_name);
		$class_name = preg_replace('/^' . CLX_SYS_PREFIX . '/', CLX_SYS_ROOT, $class_name);
		echo '<br>' . $class_name . '.php<br>';
		if(file_exists($class_name . '.php'))
			require_once $class_name . '.php';
		else 
			throw new Exception("Class is not exsist.");
	}
	
	public static function AppLoad($class_name) {
		$class_name = str_replace('\\', '/', $class_name);
		$class_name = preg_replace('/^' . CLX_APP_PREFIX . '/', CLX_APP_ROOT, $class_name);
		
		if(file_exists($class_name . '.php'))
			require_once $class_name . '.php';
		else 
			throw new Exception("Class is not exsist.");
	}
}
