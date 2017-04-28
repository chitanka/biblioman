<?php namespace App\File;

class Normalizer {

	public static function removeSpecialCharacters($filename) {
		$filename = strtr($filename, [
			' ' => '_',
			'–' => '-', // n-dash
			'—' => '-', // m-dash
		]);
		return preg_replace('/[^A-Za-z\dА-Яа-я_\-]/u', '', $filename);
	}
}
