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
	
	private function __construct() {}
	
	/**
	 * 
	 */
	public static function Method() {
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
	public static function Uri() {
		if(NULL !== self::$_uri)
			return self::$_uri;
		
		if(isset($_SERVER['PATH_INFO']))
			return self::$_uri = $_SERVER['PATH_INFO'];
		
		// Using REQUEST_URI
		elseif(isset($_SERVER['REQUEST_URI']))
			return self::$_uri = preg_replace(array('/^\/index.php/', '/\?.*$/'), '', $_SERVER['REQUEST_URI']);
		
		// Using PHP_SELF
		elseif(isset($_SERVER['PHP_SELF']))
			return self::$_uri = preg_replace('/^\/index.php/', '', $_SERVER['PHP_SELF']);
	}
	
	/**
	 * Uri
	 * 
	 * @return complex
	 */
	public static function Get() {
		if(NULL !== self::$_get)
			return self::$_get;
		
		return self::$_get = isset($_GET['params']) ? $_GET['params'] : NULL;
	}
	
	/**
	 * Post
	 * 
	 * @return complex
	 */
	public static function Post() {
		if(NULL !== self::$_post)
			return self::$_post;
		
		parse_str(file_get_contents('php://input'), $input);
		return self::$_post = isset($_POST['params']) ? $_POST['params'] : (isset($input['params']) ? $input['params'] : NULL);
	}
	
	/**
	 * Put
	 * 
	 * @return complex
	 */
	public static function Put() {
		if(NULL !== self::$_put)
			return self::$_put;
		
		parse_str(file_get_contents('php://input'), $input);
		return self::$_put = isset($input['params']) ? $input['params'] : NULL;
	}
	
	/**
	 * Delete
	 * 
	 * @return complex
	 */
	public static function Delete() {
		if(NULL !== self::$_delete)
			return self::$_delete;
		
		parse_str(file_get_contents('php://input'), $input);
		return self::$_delete = isset($input['params']) ? $input['params'] : NULL;
	}
	
	/**
	 * Files
	 * 
	 * @return complex
	 */
	public static function Files() {
		if(NULL !== self::$_files)
			return self::$_files;
		
		return self::$_files = isset($_FILES['file']) ? $_FILES['file'] : NULL;
	}
	
	/**
	 * Files
	 * 
	 * @return complex
	 */
	public static function Params() {
		switch(self::Method()) {
			case 'GET':
				return self::Get();
				break;
			case 'POST':
				return self::Post();
				break;
			case 'PUT':
				return self::Put();
				break;
			case 'DELETE':
				return self::Delete();
				break;
			default:
				return NULL;
		}
	}
}
