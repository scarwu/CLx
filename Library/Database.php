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
	
	/**
	 * @var object
	 */
	private static $_instance = NULL;
	
	/**
	 * @var object
	 */
	private $_dbh;
	
	/**
	 * @var object
	 */
	private $_sth;
	
	/**
	 * Construct
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
	 * Setting Database
	 */
	public static function SetDB($config = NULL) {
		if(NULL === self::$_instance && NULL !== $config)
			self::$_instance = new self($config);
	}
	
	/**
	 * Connect Database
	 */
	public static function Connect() {
		return NULL !== self::$_instance ? self::$_instance : NULL;
	}
	
	/**
	 * Disconnet Database
	 */
	public static function Disconnet() {
		self::$_instance = NULL;
	}
	
	/**
	 * Query Database
	 */
	public function Query($sql, $params = NULL) {
		$this->_sth = $this->_dbh->prepare($sql);
		$this->_sth->execute($params);
		return $this;
	}
	
	/**
	 * Return result as row
	 */
	public function AsRow() {
		return $this->_sth->fetchAll(PDO::FETCH_NUM);
	}
	
	/**
	 * Return result as object
	 */
	public function AsObject() {
		return $this->_sth->fetchAll(PDO::FETCH_OBJ);
	}
	
	/**
	 * Return result as array
	 */
	public function AsArray() {
		return $this->_sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Return result insert id
	 */
	public function InsertId() {
		return $this->_dbh->lastInsertId();
	}
}
