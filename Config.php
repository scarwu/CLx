<?php

/**
 * Autoload Prefix Define
 */
if(!defined('CLX_APP_PREFIX'))
	define('CLX_APP_PREFIX', 'CLxApp');

define('CLX_SYS_PREFIX', 'CLx');

/**
 * Other Define
 */
define('CLX_APP_HOST', !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('TEMP_DIR', PHP_OS == 'Linux' ? '/tmp/' : 'C:\\temp\\');
define('TIME_ZONE', 'Asia/Taipei');
