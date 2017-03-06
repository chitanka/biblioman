<?php
require __DIR__.'/server.php';
$generator = new ThumbnailServer();
$query = ltrim($generator->sanitize($_SERVER['QUERY_STRING']), '/');

if (substr_count($query, '.') == 2) {
	list($name, $width, $format) = explode('.', basename($query));
} else {
	list($name, $format) = explode('.', basename($query));
	$width = null;
}
$file = sprintf('%s/../../data/%s/%s.%s', __DIR__, dirname($query), $name, $format);

if ($width === null) {
	if (file_exists($file)) {
		return $generator->sendFile($file, $format);
	}
	return $generator->notFound($file);
}

$thumb = realpath(__DIR__ . '/../cache') . $generator->sanitize($_SERVER['REQUEST_URI']);

if (!file_exists($file)) {
	$tifFile = realpath(preg_replace('/\.[^.]+$/', '.tif', $file));
	if (!$tifFile) {
		return $generator->notFound($file);
	}
	$file = dirname($thumb) . '/orig_' . basename($file);
	if (!file_exists($file)) {
		$generator->makeSureDirExists($file);
		shell_exec("convert $tifFile $file");
	}
}
if (!file_exists($thumb)) {
	ini_set('memory_limit', '256M');
	$thumb = $generator->generateThumbnail($file, $thumb, $width, 90);
}

return $generator->sendFile($thumb, $format);
