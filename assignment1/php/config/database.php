<?php defined('SYSPATH') OR die('No direct access allowed.');

return [
	'default' => [
		'type'       => 'PDO',
		'connection' => [
			'dsn'   => 'sqlite:sqlite.db',
		],
		'table_prefix' => '',
		'charset'      => '',
		'caching'      => false,
		'profiling'    => false,
		'primary_key'  => 'id',
	],
];
