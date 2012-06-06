<?php
/**
 * CLx Event Handler
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

namespace CLx\Core;

class Event {
	private static $_instance = NULL;
	private static $_event_list;
	
	private function __construct() {
		self::$_event_list = \CLx\Core\Loader::config('Event');
	}
	
	/**
	 * Event trigger
	 */
	public static function trigger($event, $callback = NULL) {
		if(NULL === self::$_instance)
			self::$_instance = new self;
		
		if(isset(self::$_event_list[$event]))
			foreach(self::$_event_list[$event] as $handle) {
				$handle($callback);
			}
	}

}