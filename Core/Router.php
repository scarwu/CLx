<?php

class Router {
	private $_rule;
	private $_default_route;
	private $_is_match;
	
	/**
	 * 
	 */
	public function __construct() {
		$this->_rule = array();
		$this->_rule['path'] = array();
		$this->_rule['callback'] = array();
		$this->_rule['httpd_method'] = array();
		
		$this->_regex = array(
			':?' => '(.+)',
			':string' => '(\w+)',
			':numeric' => '(\d+)'
		);
		
		$this->_is_match = FALSE;
		$this->_default_route = NULL;
	}
	
	/**
	 * 
	 */
	private function segments() {
		// Using PATH_INFO
		if(isset($_SERVER['PATH_INFO']))
			return $_SERVER['PATH_INFO'];
		
		// Using PHP_SELF
		elseif(isset($_SERVER['PHP_SELF']))
			return preg_replace('/^\/index.php/', '', $_SERVER['PHP_SELF']);
		
		// Using REQUEST_URI
		elseif(isset($_SERVER['REQUEST_URI']))
			return preg_replace('/^\/index.php/', '', $_SERVER['REQUEST_URI']);
	}
	
	/**
	 * 
	 */
	private function regex_generator($path) {
		$path = str_replace(array('/', '.'), array('\/', '\.'), $path);
		
		foreach((array)$this->_regex as $search => $replace)
			$path = str_replace($search, $replace, $path);
		
		return sprintf('/^%s$/', $path);
	}
	
	/**
	 * 
	 */
	public function add($path, $callback) {
		array_push($this->_rule['path'], $path);
		array_push($this->_rule['callback'], $callback);
	}
	
	/**
	 * 
	 */
	public function run() {
		foreach((array)$this->_rule['path'] as $index => $path) {
			if('default' === $path) {
				$this->_default_route = $index;
				continue;
			}
			
			echo $path . ' => ';

			$path = $this->regex_generator($path);
			
			echo $path . ' ... ';
			
			if(preg_match($path, $this->segments())) {
				$this->_is_match = TRUE;
				$this->_rule['callback'][$index]();
				break;
			}
			else
				echo "NO\n";
		}
		
		if(FALSE === $this->_is_match) {
			if(NULL !== $this->_default_route)
				$this->_rule['callback'][$this->_default_route]();
			else {
				echo "404";
			}
		}
	}
}
