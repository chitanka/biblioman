<?php

use App\Http\Request;
use Symfony\Component\Debug\Debug;

// prevent access to debug front controllers that are deployed by accident to production servers.
if (isset($_SERVER['HTTP_CLIENT_IP'])
	|| isset($_SERVER['HTTP_X_FORWARDED_FOR'])
	|| !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || php_sapi_name() === 'cli-server')
) {
	header('HTTP/1.0 403 Forbidden');
	exit('You have no access to the development mode.');
}

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
Debug::enable();

$kernel = new App\Kernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
