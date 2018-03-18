<?php namespace App\File;

class Thumbnail {

	public static function createPath($image, $type, $width, $humanReadableName = null) {
		return '/'. implode('/', array_filter([
			'thumb',
			$type,
			self::createSubPathFromFileName($image),
			preg_replace('/\.(.+)$/', ".$width.$1", $image),
			self::normalizeHumanReadableNameForThumb($humanReadableName, $image, $width),
		]));
	}

	public static function createSubPath($objectId) {
		$subDirCount = 4;
		return implode('/', array_slice(str_split(str_pad($objectId, $subDirCount, '0', STR_PAD_LEFT)), -$subDirCount));
	}

	public static function createSubPathFromFileName($name) {
		return self::createSubPath(explode('-', $name)[0]);
	}

	private static function normalizeHumanReadableNameForThumb($name, $thumbFile, $width) {
		if ($name === null) {
			return null;
		}
		return mb_substr(Normalizer::removeSpecialCharacters($name), 0, 60) . "-{$width}px" .'.'. pathinfo($thumbFile, PATHINFO_EXTENSION);
	}
}
