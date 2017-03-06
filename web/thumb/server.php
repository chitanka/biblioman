<?php namespace App;

class ThumbnailServer {

	public function makeSureDirExists($file) {
		$dir = dirname($file);
		if ( ! file_exists($dir)) {
			mkdir($dir, 0755, true);
		}
	}

	public function generateThumbnail($filename, $thumbname, $width = null, $quality = null) {
		$this->makeSureDirExists($thumbname);

		$width = $width ?: 45;
		list($originalWidth, $originalHeight) = getimagesize($filename);
		if ($this->shouldReturnOriginalFile($width, $originalWidth)) {
			copy($filename, $thumbname);
			return $thumbname;
		}

		$height = $width * $originalHeight / $originalWidth;
		switch ($this->getExtensionFromFilename($filename)) {
			case 'jpg':
			case 'jpeg':
				return $this->generateThumbnailForJpeg($filename, $thumbname, $width, $height, $originalWidth, $originalHeight, $quality);
			case 'png':
				return $this->generateThumbnailForPng($filename, $thumbname, $width, $height, $originalWidth, $originalHeight);
		}
		return $thumbname;
	}

	private function shouldReturnOriginalFile($thumbnailWidth, $originalWidth) {
		return in_array($thumbnailWidth, ['max', 'orig']) || $thumbnailWidth > $originalWidth;
	}

	private function getExtensionFromFilename($filename) {
		return ltrim(strrchr($filename, '.'), '.');
	}

	private function generateThumbnailForJpeg($filename, $thumbname, $width, $height, $width_orig, $height_orig, $quality) {
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($filename);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		$quality = $quality ?: 90;
		imagejpeg($image_p, $thumbname, $quality);
		return $thumbname;
	}

	private function generateThumbnailForPng($filename, $thumbname, $width, $height, $width_orig, $height_orig) {
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

	public function sanitize($s) {
		$s = preg_replace('#[^a-z\d./]#', '', $s);
		$s = strtr($s, ['..' => '.']);
		return $s;
	}

	public function sendFile($file, $format) {
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

	public function notFound($file) {
		header('HTTP/1.1 404 Not Found');
		return print "File '{$file}' does not exist.";
	}
}
