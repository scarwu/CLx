<?php

define('CLX_APP_HOST', !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('TEMP_DIR', PHP_OS == 'Linux' ? '/tmp/' : 'C:\\temp\\');
define('TIME_ZONE', 'Asia/Taipei');