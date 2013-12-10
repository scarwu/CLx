<?php
/**
 * CLx Model
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

namespace CLx\Core;

abstract class Model {
	protected $_db;
	
	/**
	 * Construct
	 */
	public function __construct() {
		// Create Database Connect
		if($_database_config = \CLx\Core\Loader::config('Database', CLX_MODE)) {
			\CLx\Library\Database::setDB($_database_config);
			$this->_db = \CLx\Library\Database::connect();
		}
		else
			throw new Exception('Config/Database.php is not exists.');
	}
}
