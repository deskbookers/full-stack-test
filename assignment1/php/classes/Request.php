<?php

class Request extends Kohana_Request
{
	public static function detect_uri()
	{
		global $argv, $_GET, $_POST;

		if (php_sapi_name() == 'cli' && isset($argv) && count($argv) > 1)
		{
			$uri = $argv[1];
			if (count($argv) > 2) // <method> <uri>[?<query>]
			{
				$_SERVER['REQUEST_METHOD'] = strtoupper($argv[1]);
				$uri = $argv[2];
			}
			else
			{
				$_SERVER['REQUEST_METHOD'] = 'GET';
			}

			// Normalize URI / $_GET
			$uri = '/' . ltrim($uri, '/');
			$pos = strpos($uri, '?');
			$query = '';
			if ($pos !== false)
			{
				$query = substr($uri, $pos + 1);
				$uri = substr($uri, 0, $pos);
			}
			parse_str($query, $_GET);

			// Parse $_POST?
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				parse_str(trim(file_get_contents('php://stdin')), $_POST);
			}

			return $uri;
		}

		return parent::detect_uri();
	}
}
