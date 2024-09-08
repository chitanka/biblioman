<?php

use App\Kernel;
use App\Http\Request;
use Symfony\Component\ErrorHandler\Debug;

require __DIR__.'/cache.php';

if (isCacheable()) {
	$requestUri = filter_input(INPUT_SERVER, 'REQUEST_URI');
	if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
		$requestUri .= '.ajax';
	}
	$compressCache = !filter_input(INPUT_SERVER, 'CACHE_NOCOMPRESS');
	$varDir = __DIR__.'/../var';
	$cache = new Cache($requestUri, "$varDir/cache/simple_http_cache", "$varDir/log", $compressCache);
	if (null !== ($cachedContent = $cache->get())) {
		header("Cache-Control: public, max-age=".$cachedContent['ttl']);
		echo $cachedContent['data'];
		return;
	}
}

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
if (isset($cache) && $response->isOk()) {
	try {
		$cache->set($response->getContent(), $response->getTtl());
	} catch (\RuntimeException $e) {
		// do nothing for now; possibly log it in the future
	}
}
$response->send();
$kernel->terminate($request, $response);
