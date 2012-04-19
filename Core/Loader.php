<?php

class Loader {
	public static function Controller($controller_name, $method_name) {
		$controller_path = CLX_APP_CONTROLLERS . ucfirst($controller_name) . '.php';

		if(file_exists($controller_path)) {
			require_once $controller_path;
			
			if(is_callable($controller_name, $method_name)) {
				$controller = new $controller_name();
				$controller->$method_name();
			}
		}
	}
}
