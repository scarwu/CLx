<?php

namespace CLx\Core;

class Loader {
	public static function Controller($controller_name, $method_name) {
		$class_name = sprintf('\%s\Controllers\%s', CLX_APP_PREFIX, ucfirst($controller_name));
		$class = new $class_name();
		$class->$method_name();
	}
}
