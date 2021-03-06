<?php
/**
 * CLx Router
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */
 
namespace CLx\Core;

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
	 * @var string
	 */
	private $_uri;
	
	/**
	 * @var string
	 */
	private $_method;
	
	/**
	 * Constructor
	 */
	public function __construct($method, $uri) {
		$this->_rule = array(
			'get' => array(
				'path' => array(),
				'callback' => array(),
				'full_regex' => array()
			),
			'post' => array(
				'path' => array(),
				'callback' => array(),
				'full_regex' => array()
			),
			'put' => array(
				'path' => array(),
				'callback' => array(),
				'full_regex' => array()
			),
			'delete' => array(
				'path' => array(),
				'callback' => array(),
				'full_regex' => array()
			)
		);
		
		$this->_default_route = NULL;
		$this->_is_match = FALSE;
		
		$this->_regex = array(
			':?' => '(.+)',
			':string' => '(\w+)',
			':numeric' => '(\d+)'
		);
		
		$this->_method = strtolower($method);
		
		$this->_uri = $uri;
	}
	
	/**
	 * Regex Generator
	 * 
	 * @param string
	 * 
	 * @return string
	 */
	private function regexGenerator($path) {
		$path = str_replace(array('/', '.'), array('\/', '\.'), $path);
		
		foreach((array)$this->_regex as $search => $replace)
			$path = str_replace($search, $replace, $path);
		
		return sprintf('/^%s$/', $path);
	}
	
	/**
	 * Add Route Rule List
	 * 
	 * @param array
	 * 
	 * @return void
	 */
	public function addList($route_list) {
		foreach((array)$route_list as $method => $route)
			foreach((array)$route as $rule)
				$this->add($method, $rule[0], $rule[1], isset($rule[2]) ? $rule[2] : FALSE);
	}
	
	/**
	 * Add Route Rule
	 * 
	 * @param string
	 * @param function object
	 * @param string
	 * @param bool
	 * 
	 * @return void
	 */
	public function add($method = 'get', $path, $callback, $full_regex = FALSE) {
		$method = strtolower($method);
		
		if(!isset($this->_rule[$method]))
			$method = 'get';
		
		$this->_rule[$method]['path'][] = $path;
		$this->_rule[$method]['callback'][] = $callback;
		$this->_rule[$method]['full_regex'][] = $full_regex;
	}
	
	/**
	 * Run Router
	 * 
	 * @return void
	 */
	public function run() {
		foreach((array)$this->_rule[$this->_method]['path'] as $index => $path) {
			if('default' === $path) {
				$this->_default_route = $index;
				continue;
			}
			
			if(!$this->_rule[$this->_method]['full_regex'][$index])
				$path = $this->regexGenerator($path);
			
			if(preg_match($path, $this->_uri, $match)) {
				$this->_is_match = TRUE;
				$this->_rule[$this->_method]['callback'][$index](array_slice($match, 1));
				break;
			}
		}
		
		if(FALSE === $this->_is_match) {
			if(NULL !== $this->_default_route)
				$this->_rule[$this->_method]['callback'][$this->_default_route]();
			else
				Response::setCode(404);
		}
	}
}
