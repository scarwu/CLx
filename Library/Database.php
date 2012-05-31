<?php
/**
 * CLx Database Library
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

namespace CLx\Library;
use Exception;
use PDO;

class Database {
	private static $_instance = NULL;
	
	private $_dbh;
	
	private $_sth;
	
	/**
	 * 
	 */
	private function __construct($config) {
		if(!class_exists('PDO'))
			throw new Exception('PDO is not exists.');

		$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $config['host'], $config['port'], $config['name']);
		$this->_dbh = new PDO($dsn, $config['user'], $config['pass']);
		
		// Set connection based on utf-8 encode for mysql
		if($config['type'] == 'mysql') {
			$this->_dbh->Query("SET NAMES 'utf8'");
			$this->_dbh->Query("SET CHARACTER_SET_CLIENT=utf8");
			$this->_dbh->Query("SET CHARACTER_SET_RESULTS=utf8");
		}
	}
	
	/**
	 * 
	 */
	public static function SetDB($config = NULL) {
		if(NULL === self::$_instance && NULL !== $config)
			self::$_instance = new self($config);
	}
	
	/**
	 * 
	 */
	public function Connect() {
		return NULL !== self::$_instance ? self::$_instance : NULL;
	}
	
	/**
	 * 
	 */
	public function Disconnet() {
		self::$_instance = NULL;
	}
	
	/**
	 * 
	 */
	public function Query($sql, $params = NULL) {
		$this->_sth = $this->_dbh->prepare($sql);
		$this->_sth->execute($params);
		$this->clear();
		return $this;
	}
	
	/**
	 * 
	 */
	public function AsRow() {
		return $this->_sth->fetchAll(PDO::FETCH_NUM);
	}
	
	/**
	 * 
	 */
	public function AsObject() {
		return $this->_sth->fetchAll(PDO::FETCH_OBJ);
	}
	
	/**
	 * 
	 */
	public function AsArray() {
		return $this->_sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * 
	 */
	public function InsertId() {
		return $this->_dbh->lastInsertId();
	}
}
