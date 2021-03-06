<?php
/**
 * CLx Loader
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

namespace CLx\Core;

class Loader {
	
	private function __construct() {}
	
	/**
	 * Library Loader
	 * 
	 * @param string
	 * 
	 * @return boolean
	 */
	public static function config($_config_name, $_config_index = NULL) {
		$_config_path = sprintf('%s/Config/%s.php', CLX_APP_ROOT, $_config_name);
		if(!file_exists($_config_path))
			return FALSE;

		include $_config_path;
		
		if(NULL != $_config_index && isset(${$_config_name}[$_config_index]))
			return ${$_config_name}[$_config_index];
		
		return isset(${$_config_name}) ? ${$_config_name} : FALSE;
	}
	
	/**
	 * Library Loader
	 * 
	 * @param string
	 * 
	 * @return boolean
	 */
	public static function library($_library_name) {
		$_library_path = sprintf('%s/Library/%s.php', CLX_APP_ROOT, $_library_name);
		if(!file_exists($_library_path))
			return FALSE;

		require_once $_library_path;

		return TRUE;
	}
	
	/**
	 * Model Loader
	 * 
	 * @param string
	 * 
	 * @return object
	 */
	public static function model($_model_name) {
		$_model_name = ucfirst($_model_name) . 'Model';
		$_model_path = sprintf('%s/Models/%s.php', CLX_APP_ROOT, $_model_name);
		if(!file_exists($_model_path))
			return FALSE;

		require_once $_model_path;

		return new $_model_name();
	}
	
	/**
	 * View Loader
	 * 
	 * @param string
	 * @param array
	 * @param boolean
	 * 
	 * @return string
	 */
	public static function view($_view_name, $_view_data = NULL, $_view_output = FALSE) {
		$_view_path = sprintf('%s/Views/%s.php', CLX_APP_ROOT, $_view_name);
		if(!file_exists($_view_path))
			return FALSE;

		if(NULL !== $_view_data)
			foreach ($_view_data as $_key => $_value)
				$$_key = $_value;
				
		if(!$_view_output)
			include $_view_path;
		else {
			ob_start();
			ob_clean();
			include $_view_path;
			$_view_result = ob_get_contents();
			ob_end_clean();
			return $_view_result;
		}
	}
	
	/**
	 * Controller Loader
	 * 
	 * @param string
	 * @param string
	 * @param boolean
	 * 
	 * @return boolean
	 */
	public static function controller($_controller_name, $_function_name, $_function_params = NULL) {
		$_controller_name = ucfirst($_controller_name) . 'Controller';
		$_controller_path = sprintf('%s/Controllers/%s.php', CLX_APP_ROOT, $_controller_name);
		if(!file_exists($_controller_path))
			return FALSE;

		require_once $_controller_path;

		if(!method_exists($_controller_name, $_function_name))
			return FALSE;
		
		$_class = new $_controller_name();
		
		// Call filter before
		$_class->_before();
		
		// Call requested function
		$_class->$_function_name($_function_params);
		
		// Call filter after
		$_class->_after();
		return TRUE;
	}
}
