<?php namespace App\Entity\BookField;

class Illustrated extends BookField {

	public static function normalizeInput($input) {
		return in_array($input, ['да', '1', 'true']);
	}
}
