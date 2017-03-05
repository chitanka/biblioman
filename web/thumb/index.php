<?php
function makeSureDirExists($file) {
	$dir = dirname($file);
	if ( ! file_exists($dir)) {
		mkdir($dir, 0755, true);
	}
}

function generateThumbnail($filename, $thumbname, $width = null, $quality = null) {
	makeSureDirExists($thumbname);

	$width = $width ?: 45;
	list($originalWidth, $originalHeight) = getimagesize($filename);
	if (shouldReturnOriginalFile($width, $originalWidth)) {
		copy($filename, $thumbname);
		return $thumbname;
	}

	$height = $width * $originalHeight / $originalWidth;
	switch (getExtensionFromFilename($filename)) {
		case 'jpg':
		case 'jpeg':
			return generateThumbnailForJpeg($filename, $thumbname, $width, $height, $originalWidth, $originalHeight, $quality);
		case 'png':
			return generateThumbnailForPng($filename, $thumbname, $width, $height, $originalWidth, $originalHeight);
	}
	return $thumbname;
}

function shouldReturnOriginalFile($thumbnailWidth, $originalWidth) {
	return in_array($thumbnailWidth, ['max', 'orig']) || $thumbnailWidth > $originalWidth;
}

function getExtensionFromFilename($filename) {
	return ltrim(strrchr($filename, '.'), '.');
}

function generateThumbnailForJpeg($filename, $thumbname, $width, $height, $width_orig, $height_orig, $quality) {
	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefromjpeg($filename);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	$quality = $quality ?: 90;
	imagejpeg($image_p, $thumbname, $quality);
	return $thumbname;
}

function generateThumbnailForPng($filename, $thumbname, $width, $height, $width_orig, $height_orig) {
	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefrompng($filename);
	imagealphablending($image_p, false);
	$color = imagecolortransparent($image_p, imagecolorallocatealpha($image_p, 0, 0, 0, 127));
	imagefill($image_p, 0, 0, $color);
	imagesavealpha($image_p, true);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	imagepng($image_p, $thumbname, 9);
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
	header('Content-Length: '.filesize($file));
	return readfile($file);
}

function notFound($file) {
	header('HTTP/1.1 404 Not Found');
	return print "File '{$file}' does not exist.";
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
		return sendFile($file, $format);
	}
	return notFound($file);
}

$thumb = realpath(__DIR__ . '/../cache') . sanitize($_SERVER['REQUEST_URI']);

if (!file_exists($file)) {
	$tifFile = realpath(preg_replace('/\.[^.]+$/', '.tif', $file));
	if (!$tifFile) {
		return notFound($file);
	}
	$file = dirname($thumb) . '/orig_' . basename($file);
	if (!file_exists($file)) {
		makeSureDirExists($file);
		shell_exec("convert $tifFile $file");
	}
}
if (!file_exists($thumb)) {
	ini_set('memory_limit', '256M');
	$thumb = generateThumbnail($file, $thumb, $width, 90);
}

return sendFile($thumb, $format);
