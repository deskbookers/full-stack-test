<?php

// Setup error handling
require_once(__DIR__ . '/errors.php');

// Load external dependencies
require_once(__DIR__ . '/vendor/autoload.php');

// Setup Kohana
define('EXT', '.php');
define('DOCROOT', __DIR__ . '/');
define('MODPATH', DOCROOT . 'modules/');
define('SYSPATH', DOCROOT . 'vendor/kohana/core/');
define('APPPATH', DOCROOT);
require_once(SYSPATH . '/classes/Kohana/Core.php');
require_once(SYSPATH . '/classes/Kohana.php');
spl_autoload_register(array('Kohana', 'auto_load'));
ini_set('unserialize_callback_func', 'spl_autoload_call');
Kohana::init([
	'cache_dir' => DOCROOT . 'cache/',
	'errors' => true,
	'caching' => false,
]);
Kohana::modules([
	MODPATH . 'database',
	MODPATH . 'orm',
]);
I18n::lang('en');
Kohana::$log->attach(new Log_File(DOCROOT . 'logs'));
Kohana::$config->attach(new Config_File);
