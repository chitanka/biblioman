<?php
function makeSureDirExists($file) {
	$dir = dirname($file);
	if ( ! file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
}

function genThumbnail($filename, $thumbname, $width = null, $quality = null) {
	makeSureDirExists($thumbname);

	$width = $width ?: 45;
	$quality = $quality ?: 90;
	list($width_orig, $height_orig) = getimagesize($filename);
	if ($width == 'max' || $width == 'orig' || $width_orig < $width) {
		copy($filename, $thumbname);

		return $thumbname;
	}

	$height = $width * $height_orig / $width_orig;

	$image_p = imagecreatetruecolor($width, $height);

	$extension = ltrim(strrchr($filename, '.'), '.');
	switch ($extension) {
		case 'jpg':
		case 'jpeg':
			$image = imagecreatefromjpeg($filename);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			imagejpeg($image_p, $thumbname, $quality);
			break;
		case 'png':
			$image = imagecreatefrompng($filename);
			imagealphablending($image_p, false);
			$color = imagecolortransparent($image_p, imagecolorallocatealpha($image_p, 0, 0, 0, 127));
			imagefill($image_p, 0, 0, $color);
			imagesavealpha($image_p, true);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			imagepng($image_p, $thumbname, 9);
			break;
	}

	return $thumbname;
}

function sanitize($s) {
	$s = preg_replace('#[^a-z\d./]#', '', $s);
	$s = strtr($s, ['..' => '.']);
	return $s;
}

function sendFile($file, $format) {
	$format = strtr($format, [
		'jpg' => 'jpeg',
		'tif' => 'tiff',
	]);
	$expires = 2592000; // 30 days
	header("Cache-Control: maxage=$expires");
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
	header('Content-Type: image/'.$format);
	readfile($file);
}

function notFound($file) {
	header('HTTP/1.1 404 Not Found');
	error_log($file . ' not found');
	die();
}

$query = ltrim(sanitize($_SERVER['QUERY_STRING']), '/');

if (substr_count($query, '.') == 2) {
	list($name, $width, $format) = explode('.', basename($query));
} else {
	list($name, $format) = explode('.', basename($query));
	$width = null;
}
$file = sprintf('%s/../../data/%s/%s.%s', __DIR__, dirname($query), $name, $format);

if ($width === null) {
	if (file_exists($file)) {
		sendFile($file, $format);
	} else {
		notFound($file);
	}
}

$thumb = realpath(__DIR__ . '/../cache') . sanitize($_SERVER['REQUEST_URI']);

if (!file_exists($file)) {
	if ($format != 'png') {
		notFound($file);
	}
	$tifFile = realpath(str_replace('.png', '.tif', $file));
	if (!$tifFile) {
		notFound($file);
	}
	$file = dirname($thumb) . '/orig_' . basename($file);
	if (!file_exists($file)) {
		makeSureDirExists($file);
		shell_exec("convert $tifFile $file");
	}
}
if (!file_exists($thumb)) {
	ini_set('memory_limit', '256M');
	$thumb = genThumbnail($file, $thumb, $width, 90);
}

sendFile($thumb, $format);
