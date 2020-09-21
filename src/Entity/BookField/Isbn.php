<?php namespace App\Entity\BookField;

class Isbn extends BookField {

	public static function normalizeInput($input) {
		$isbnFixed = strtr($input, [
			'Х' => 'X', // replace cyrillic Х
			'–' => '-',
			'—' => '-',
		]);
		return $isbnFixed;
	}
}
