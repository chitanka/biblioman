<?php namespace App\Entity\BookField;

use App\Php\RegExp;

class Publisher extends BookField {

	private static $publisherPrefixes = [
		'Издателска къща',
		'ИК',
		'Издателство',
		'Издателска компания',
		'Издателска група',
		'Книгоиздателска къща',
		'КК',
		'Държавно издателство',
		'ДИ',
		'ДФ',
	];

	private static $publisherStringsToRemove = [
		'"',
		'„',
		'“',
		'«',
		'»',
		' ООД',
		' ЕООД',
		' АД',
		'Издателство на ЦК на ДКМС',
		'издателство на ЦК на ДКМС',
		'Университетско издателство',
		'Ltd',
		' —',
	];

	public static function normalizeInput($input) {
		$nameNormalized = $input;
		$nameNormalized = preg_replace('/^('.RegExp::gluePrefixesForRegExp(self::$publisherPrefixes).') ["„«]?/u', '', $nameNormalized);
		$nameNormalized = str_replace(self::$publisherStringsToRemove, '', $nameNormalized);
		$nameNormalized = trim($nameNormalized, ' ,-');
		if (empty($nameNormalized)) {
			// we do not want to be perfect
			return $input;
		}
		return $nameNormalized;
	}
}
