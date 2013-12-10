<?php
/**
 * CLx Controller
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

namespace CLx\Core;

abstract class Controller {

	public function __construct() {}
	
	/**
	 * Before filter
	 */
	public function _before() {}
	
	/**
	 * After filter
	 */
	public function _after() {}
}
