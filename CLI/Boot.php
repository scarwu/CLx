<?php
/**
 * Load NanoCLI and Setting
 */
require_once ROOT . 'NanoCLI/NanoCLI.php';
require_once ROOT . 'NanoCLI/NanoIO.php';
require_once ROOT . 'NanoCLI/NanoLoader.php';

// Default Setting
define('NANOCLI_COMMAND', ROOT . 'Command' . SEPARATOR);
define('NANOCLI_PREFIX', 'pointless');

// Register NanoCLI Autoloader
NanoLoader::Register();

// Load First Command and Init
require_once NANOCLI_COMMAND . 'clx.php';

$NanoCLI = new clx();
$NanoCLI->Init();
