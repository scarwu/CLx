#!/usr/bin/php

<?php
define('SEPARATOR', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(realpath($_SERVER['PHP_SELF'])) . SEPARATOR . '..') . SEPARATOR);
define('PWD', $_SERVER['PWD'] . DIRECTORY_SEPARATOR);

require_once ROOT . 'CLI.php';

// Autoload
function autoload($className) {
	$className = str_replace('_', '/', $className);
	$className = preg_replace('/^clx/', ROOT . 'Function' , $className);

	require_once "$className.php";
}

spl_autoload_register('autoload');

class clx extends CLI {
	public function __construct() {
		parent::__construct();
		CLI::$prefix = 'clx';
	}
	
	public function run() {
		// Clean static pages
		$clean = new clx_help();
		$clean->run();
	}
}

$CLI = new clx();
$CLI->init();