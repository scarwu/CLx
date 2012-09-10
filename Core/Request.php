<?php
/**
 * CLx Request
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

namespace CLx\Core;

class Request {
	private static $_uri = NULL;
	private static $_post = NULL;
	private static $_get = NULL;
	private static $_put = NULL;
	private static $_delete = NULL;
	private static $_files = NULL;
	private static $_method = NULL;
	private static $_params = NULL;
	private static $_headers = NULL;
	
	private function __construct() {}
	
	/**
	 * HTTP Method
	 * 
	 * @return string
	 */
	public static function method() {
		if(NULL !== self::$_method)
			return self::$_method;
		
		return self::$_method = isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) 
			? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']
			: $_SERVER['REQUEST_METHOD'];
	}
	
	/**
	 * Uri
	 * 
	 * @return string
	 */
	public static function uri() {
		if(NULL !== self::$_uri)
			return self::$_uri;
		
		if(isset($_SERVER['PATH_INFO']))
			return self::$_uri = $_SERVER['PATH_INFO'];
		
		// Using REQUEST_URI
		elseif(isset($_SERVER['REQUEST_URI']))
			return self::$_uri = urldecode(preg_replace(array('/^\/index.php/', '/\?.*$/'), '', $_SERVER['REQUEST_URI']));
		
		// Using PHP_SELF
		elseif(isset($_SERVER['PHP_SELF']))
			return self::$_uri = preg_replace('/^\/index.php/', '', $_SERVER['PHP_SELF']);
	}
	
	/**
	 * Get
	 * 
	 * @return array
	 */
	public static function get() {
		if(NULL !== self::$_get)
			return self::$_get;
		
		return self::$_get = $_GET;
	}
	
	/**
	 * Post
	 * 
	 * @return array
	 */
	public static function post() {
		if(NULL !== self::$_post)
			return self::$_post;
		
		return self::$_post = $_POST;
	}
	
	/**
	 * Files
	 * 
	 * @return array
	 */
	public static function files() {
		if(NULL !== self::$_files)
			return self::$_files;
		
		return self::$_files = isset($_FILES['file']) ? $_FILES['file'] : NULL;
	}
	
	/**
	 * Parameters
	 * 
	 * @return array
	 */
	public static function params() {
		if(NULL !== self::$_params)
			return self::$_params;
		
		switch(self::method()) {
			case 'GET':
				if(preg_match('/({(?:.|\n)+})(?:(?:.|\n)+)?/', urldecode($_SERVER['QUERY_STRING']), $match)) {
					if(isset($match[1]))
						return self::$_params = json_decode($match[1], TRUE);
					else
						return $match[0];
				}
				else
					return NULL;
				break;
			case 'POST':
			case 'PUT':
			case 'DELETE':
				return self::$_params = json_decode(file_get_contents('php://input'), TRUE);
				break;
			default:
				return NULL;
		}
	}
	
	/**
	 * Headers
	 * 
	 * @return array
	 */
	public static function headers() {
		if(NULL !== self::$_headers)
			return self::$_headers;
		
		foreach($_SERVER as $key => $value)
			if(preg_match('/^HTTP_(.+)/', $key, $match)) {
				$names = explode('_', $match[1]);
				$index = ucfirst(strtolower(array_shift($names)));
				if(count($names))
					foreach($names as $segments)
						$index .= '-' . ucfirst(strtolower($segments));
				self::$_headers[$index] = $value;
			}
		
		if(isset($_SERVER['CONTENT_LENGTH']))
			self::$_headers['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
		
		if(isset($_SERVER['CONTENT_TYPE']))
			self::$_headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
		
		return self::$_headers;
	}
}
