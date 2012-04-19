<?php

class Router {
	
	/**
	 * @var array
	 */
	private $_rule;
	
	/**
	 * @var int
	 */
	private $_default_route;
	
	/**
	 * @var bool
	 */
	private $_is_match;
	
	/**
	 * @var array
	 */
	private $_regex;
	
	/**
	 * Constructor
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
	 * Regex Generator
	 * 
	 * @param string
	 * 
	 * @return string
	 */
	private function RegexGenerator($path) {
		$path = str_replace(array('/', '.'), array('\/', '\.'), $path);
		
		foreach((array)$this->_regex as $search => $replace)
			$path = str_replace($search, $replace, $path);
		
		return sprintf('/^%s$/', $path);
	}
	
	/**
	 * Add Route Rule
	 * 
	 * @param string
	 * @param function object
	 * 
	 * @return void
	 */
	public function Add($path, $callback) {
		array_push($this->_rule['path'], $path);
		array_push($this->_rule['callback'], $callback);
	}
	
	/**
	 * Run Router
	 * 
	 * @return void
	 */
	public function Run() {
		foreach((array)$this->_rule['path'] as $index => $path) {
			if('default' === $path) {
				$this->_default_route = $index;
				continue;
			}
			
			$path = $this->RegexGenerator($path);
			
			if(preg_match($path, Request::Uri(), $match)) {
				$this->_is_match = TRUE;
				
				$this->_rule['callback'][$index](array_slice($match, 1));
				break;
			}
		}
		
		if(FALSE === $this->_is_match) {
			if(NULL !== $this->_default_route)
				$this->_rule['callback'][$this->_default_route]();
			else
				Response::HTTPCode(404);
		}
	}
}
