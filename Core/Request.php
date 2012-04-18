<?php

class Request {
	private static $uri = NULL;
	
	/**
	 * 
	 */
	public static function Uri() {
		if(NULL !== self::$uri)
			return self::$uri;
		
		if(isset($_SERVER['PATH_INFO']))
			return self::$uri = $_SERVER['PATH_INFO'];
		
		// Using PHP_SELF
		elseif(isset($_SERVER['PHP_SELF']))
			return self::$uri = preg_replace('/^\/index.php/', '', $_SERVER['PHP_SELF']);
		
		// Using REQUEST_URI
		elseif(isset($_SERVER['REQUEST_URI']))
			return self::$uri = preg_replace('/^\/index.php/', '', $_SERVER['REQUEST_URI']);
	}
}
