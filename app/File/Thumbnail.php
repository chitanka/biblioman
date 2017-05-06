<?php namespace App\File;

class Thumbnail {

	public static function createPath($image, $type, $width, $humanReadableName = null) {
		return '/'. implode('/', array_filter([
			'thumb',
			$type,
			preg_replace('/\.(.+)$/', ".$width.$1", $image),
			self::normalizeHumanReadableNameForThumb($humanReadableName, $image, $width),
		]));
	}

	private static function normalizeHumanReadableNameForThumb($name, $thumbFile, $width) {
		if ($name === null) {
			return null;
		}
		return mb_substr(Normalizer::removeSpecialCharacters($name), 0, 60) . "-{$width}px" .'.'. pathinfo($thumbFile, PATHINFO_EXTENSION);
	}
}
