<?php namespace App\Entity\BookField;

use App\Php\RegExp;

class Person extends BookField {

	private static $personPrefixes = [
		'д-р',
		'проф.',
		'проф. д-р',
		'акад.',
		'инж.',
	];

	public static function normalizeInput($input) {
		$nameNormalized = $input;
		$nameNormalized = preg_replace('/^('.RegExp::gluePrefixesForRegExp(self::$personPrefixes).') /u', '', $nameNormalized);
		$nameNormalized = preg_replace('/ \(.+\)$/', '', $nameNormalized);
		return $nameNormalized;
	}

}
