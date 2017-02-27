<?php namespace App\Library;

class BookField {

	public static function normalizedFieldValue($field, $value) {
		$value = self::normalizeGenericValue($value);
		switch ($field) {
			case 'author':
			case 'otherAuthors':
			case 'adaptedBy':
			case 'translator':
			case 'compiler':
			case 'editorialStaff':
			case 'chiefEditor':
			case 'editor':
			case 'publisherEditor':
			case 'consultant':
			case 'artist':
			case 'artistEditor':
			case 'technicalEditor':
			case 'reviewer':
			case 'corrector':
				return self::normalizePerson($value);
			case 'publisher':
				return self::normalizePublisher($value);
			case 'illustrated':
				return self::normalizeIllustrated($value);
			case 'isbn':
				return self::normalizeIsbn($value);
			case 'isbnClean':
				return self::normalizeSearchableIsbn($value);
		}
		$value = trim($value);
		return $value;
	}

	private static function normalizePerson($name) {
		$nameNormalized = $name;
		$prefixes = [
			'д-р',
			'проф.',
			'проф. д-р',
			'акад.',
			'инж.',
		];
		$nameNormalized = preg_replace('/^('.self::gluePrefixesForRegExp($prefixes).') /u', '', $nameNormalized);
		$nameNormalized = preg_replace('/ \(.+\)$/', '', $nameNormalized);
		return $nameNormalized;
	}

	private static function normalizePublisher($name) {
		$nameNormalized = trim($name);
		$prefixes = [
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
		$nameNormalized = preg_replace('/^('.self::gluePrefixesForRegExp($prefixes).') ["„«]?/u', '', $nameNormalized);
		$nameNormalized = strtr($nameNormalized, [
			'"' => '',
			'„' => '',
			'“' => '',
			'«' => '',
			'»' => '',
			' ООД' => '',
			' ЕООД' => '',
			' АД' => '',
			'Издателство на ЦК на ДКМС' => '',
			'издателство на ЦК на ДКМС' => '',
			'Университетско издателство' => '',
			'Ltd' => '',
			' —' => '',
		]);
		$nameNormalized = trim($nameNormalized, ' ,-');
		if (empty($nameNormalized)) {
			// we do not want to be perfect
			return $name;
		}
		return $nameNormalized;
	}

	private static function normalizeGenericValue($value) {
		return preg_replace('/ \(не е указан[ао]?|не е посочен[ао]?\)/u', '', $value);
	}

	private static function normalizeIllustrated($value) {
		return in_array($value, ['да', '1', 'true']) ? 1 : 0;
	}

	public static function normalizeIsbn($isbn) {
		$isbnFixed = strtr($isbn, [
			'Х' => 'X', // replace cyrillic Х
			'–' => '-',
			'—' => '-',
		]);
		return $isbnFixed;
	}

	public static function normalizeSearchableIsbn($isbn) {
		return preg_replace('/[^\dX,]/', '', self::normalizeIsbn($isbn));
	}

	private static function gluePrefixesForRegExp($prefixes) {
		return implode('|', array_map('preg_quote', $prefixes));
	}

}
