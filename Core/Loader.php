<?php
/**
 * 
 */

namespace CLx\Core;

class Loader {
	
	/**
	 * Model Loader
	 * 
	 * @param string
	 * 
	 * @return object
	 */
	public static function Model($_model_name) {
		$_model_name = ucfirst($_model_name);
		if(class_exists($_model_name))
			return new $_model_name();
		
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
	public static function View($_view_name, $_view_data = NULL, $_view_output = FALSE) {
		$_view_path = sprintf('%s/Views/%s.html', CLX_APP_ROOT, $_view_name);
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
	public static function Controller($_controller_name, $_method_name, $_method_params = NULL) {
		$_controller_name = ucfirst($_controller_name);
		$_controller_path = sprintf('%s/Controllers/%s.php', CLX_APP_ROOT, $_controller_name);
		if(!file_exists($_controller_path))
			return FALSE;

		require_once $_controller_path;

		if(!method_exists($_controller_name, $_method_name))
			return FALSE;
		
		$_class = new $_controller_name();
		$_class->$_method_name($_method_params);
		return TRUE;
	}
}
