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
		
		$this->_is_match = FALSE;
		$this->_default_route = NULL;
		
		$this->_regex = array(
			':?' => '(.+)',
			':string' => '(\w+)',
			':numeric' => '(\d+)'
		);
	}
	
	/**
	 * 
	 */
	private function RegexGenerator($path) {
		$path = str_replace(array('/', '.'), array('\/', '\.'), $path);
		
		foreach((array)$this->_regex as $search => $replace)
			$path = str_replace($search, $replace, $path);
		
		return sprintf('/^%s$/', $path);
	}
	
	/**
	 * 
	 */
	public function Add($path, $callback) {
		array_push($this->_rule['path'], $path);
		array_push($this->_rule['callback'], $callback);
	}
	
	/**
	 * 
	 */
	public function Run() {
		foreach((array)$this->_rule['path'] as $index => $path) {
			if('default' === $path) {
				$this->_default_route = $index;
				continue;
			}
			
			$path = $this->RegexGenerator($path);
			
			if(preg_match($path, Request::Uri())) {
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
