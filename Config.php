<?php
/**
 * CLx Config
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

/**
 * Autoload Prefix Define
 */
define('CLX_SYS_PREFIX', 'CLx');

/**
 * Other Define
 */
define('CLX_APP_HOST', !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('TEMP_DIR', PHP_OS == 'Linux' ? '/tmp/' : 'C:\\temp\\');
define('TIME_ZONE', 'Asia/Taipei');
