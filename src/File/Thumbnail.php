<?php namespace App\File;

class Thumbnail {

	public static function createCoverPath($image, $width, $humanReadableName = null) {
		return static::createPath($image, Path::DIR_COVERS, $width, $humanReadableName);
	}

	public static function createScanPath($image, $width, $humanReadableName = null) {
		return static::createPath($image, Path::DIR_SCANS, $width, $humanReadableName);
	}

	public static function createPath($image, $type, $width, $humanReadableName = null) {
		if (empty($image)) {
			return null;
		}
		return 'https://biblioman.chitanka.info'.Path::SEP. implode(Path::SEP, array_filter([
			Path::DIR_THUMB,
			$type,
			self::createSubPathFromFileName($image),
			preg_replace('/\.(.+)$/', ".$width.$1", $image),
			self::normalizeHumanReadableNameForThumb($humanReadableName, $image, $width),
		]));
	}

	public static function createSubPath($objectId) {
		if (!is_numeric($objectId)) {
			return '';
		}
		$subDirCount = 4;
		return implode(Path::SEP, array_slice(str_split(str_pad($objectId, $subDirCount, '0', STR_PAD_LEFT)), -$subDirCount));
	}

	public static function createSubPathFromFileName($name) {
		return self::createSubPath(explode('-', $name)[0]);
	}

	public static function normalizeHumanReadableNameForFile($name, $file) {
		if ($name === null) {
			return null;
		}
		return mb_substr(Normalizer::removeSpecialCharacters($name), 0, 60) .'.'. pathinfo($file, PATHINFO_EXTENSION);
	}

	private static function normalizeHumanReadableNameForThumb($name, $thumbFile, $width) {
		if ($name === null) {
			return null;
		}
		return mb_substr(Normalizer::removeSpecialCharacters($name), 0, 60) . "-{$width}px" .'.'. pathinfo($thumbFile, PATHINFO_EXTENSION);
	}
}
