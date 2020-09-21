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

	private static $alternativeNames = [
		'Чарлс ' => 'Чарлз ',
	];

	public static function normalizeInput($input) {
		$nameNormalized = $input;
		$nameNormalized = preg_replace('/^('.RegExp::gluePrefixesForRegExp(self::$personPrefixes).') /u', '', $nameNormalized);
		$nameNormalized = preg_replace('/ \(.+\)$/', '', $nameNormalized);
		$nameNormalized = self::normalizeAlternativeNames($nameNormalized);
		return $nameNormalized;
	}

	public static function normalizeAlternativeNames($input) {
		return strtr($input, self::$alternativeNames);
	}
}
