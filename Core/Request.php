<?php

class Request {
	private static $_uri = NULL;
	
	/**
	 * Uri
	 * 
	 * @return string
	 */
	public static function Uri() {
		if(NULL !== self::$_uri)
			return self::$_uri;
		
		if(isset($_SERVER['PATH_INFO']))
			return self::$_uri = $_SERVER['PATH_INFO'];
		
		// Using PHP_SELF
		elseif(isset($_SERVER['PHP_SELF']))
			return self::$_uri = preg_replace('/^\/index.php/', '', $_SERVER['PHP_SELF']);
		
		// Using REQUEST_URI
		if(isset($_SERVER['REQUEST_URI']))
			return self::$_uri = preg_replace(array('/^\/index.php/', '/\?.*$/'), '', $_SERVER['REQUEST_URI']);
	}
	
	/**
	 * Uri
	 * 
	 * @return complex
	 */
	public static function Get() {
		
	}
	
	/**
	 * Post
	 * 
	 * @return complex
	 */
	public static function Post() {
		
	}
	
	/**
	 * Put
	 * 
	 * @return complex
	 */
	public static function Put() {
		
	}
	
	/**
	 * Delete
	 * 
	 * @return complex
	 */
	public static function Delete() {
		
	}
	
	/**
	 * Files
	 * 
	 * @return complex
	 */
	public static function Files() {
		
	}
}
