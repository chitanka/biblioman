<?php
function genThumbnail($filename, $thumbname, $width = null, $quality = null) {
	$dir = dirname($thumbname);
	if ( ! file_exists($dir)) {
		mkdir($dir, 0755, true);
	}

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


$query = ltrim($_SERVER['QUERY_STRING'], '/');
$query = strtr($query, array('..' => '.'));

if (substr_count($query, '.') == 2) {
	list($name, $width, $format) = explode('.', basename($query));
} else {
	list($name, $format) = explode('.', basename($query));
	$width = null;
}
$file = sprintf('%s/../../data/scans/%s/%s.%s', dirname(__FILE__), dirname($query), $name, $format);

if ($format == 'jpg') {
	$format = 'jpeg';
}

if (file_exists($file)) {
	$expires = 30240000; // 350 days
	header("Cache-Control: maxage=$expires");
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
	header('Content-Type: image/'.$format);
	$thumb = dirname(__FILE__) . '/../cache' . $_SERVER['REQUEST_URI'];
	ini_set('memory_limit', '256M');
	readfile(genThumbnail($file, $thumb, $width, 90));
} else {
	header('HTTP/1.1 404 Not Found');
	error_log($file.' not found');
}
