<?php namespace App\Library;

class BookField {

	const PROPERTY_MAP = [
		'title' => 'titling',
		'altTitle' => 'titling',
		'subtitle' => 'titling',
		'subtitle2' => 'titling',
		'volumeTitle' => 'titling',
		'author' => 'authorship',
		'translator' => 'authorship',
		'translatedFromLanguage' => 'authorship',
		'dateOfTranslation' => 'authorship',
		'adaptedBy' => 'authorship',
		'otherAuthors' => 'authorship',
		'compiler' => 'authorship',
		'format' => 'body',
		'pageCount' => 'body',
		'binding' => 'body',
		'themes' => 'classification',
		'genre' => 'classification',
		'category' => 'classification',
		'trackingCode' => 'classification',
		'litGroup' => 'classification',
		'uniformProductClassification' => 'classification',
		'universalDecimalClassification' => 'classification',
		'isbn' => 'classification',
		'contentType' => 'content',
		'nationality' => 'content',
		'language' => 'content',
		'notesAboutOriginal' => 'content',
		'annotation' => 'content',
		'notesAboutAuthor' => 'content',
		'marketingSnippets' => 'content',
		'toc' => 'content',
		'illustrated' => 'content',
		'sequence' => 'grouping',
		'sequenceNr' => 'grouping',
		'subsequence' => 'grouping',
		'subsequenceNr' => 'grouping',
		'series' => 'grouping',
		'seriesNr' => 'grouping',
		'printingHouse' => 'print',
		'typeSettingIn' => 'print',
		'printSigned' => 'print',
		'printOut' => 'print',
		'printerSheets' => 'print',
		'publisherSheets' => 'print',
		'provisionPublisherSheets' => 'print',
		'totalPrint' => 'print',
		'chiefEditor' => 'staff',
		'managingEditor' => 'staff',
		'editor' => 'staff',
		'editorialStaff' => 'staff',
		'publisherEditor' => 'staff',
		'artistEditor' => 'staff',
		'technicalEditor' => 'staff',
		'consultant' => 'staff',
		'scienceEditor' => 'staff',
		'copyreader' => 'staff',
		'reviewer' => 'staff',
		'artist' => 'staff',
		'illustrator' => 'staff',
		'corrector' => 'staff',
		'layout' => 'staff',
		'coverLayout' => 'staff',
		'libraryDesign' => 'staff',
		'computerProcessing' => 'staff',
		'prepress' => 'staff',
		'edition' => 'publishing',
		'publisher' => 'publishing',
		'publisherCity' => 'publishing',
		'publishingYear' => 'publishing',
		'publisherAddress' => 'publishing',
		'publisherCode' => 'publishing',
		'publisherOrder' => 'publishing',
		'publisherNumber' => 'publishing',
		'price' => 'publishing',
		'notes' => 'meta',
		'infoSources' => 'meta',
		'adminComment' => 'meta',
		'ocredText' => 'meta',
		'isIncomplete' => 'meta',
		'reasonWhyIncomplete' => 'meta',
		'verified' => 'meta',
	];

	private static $personFields = [
		'author',
		'otherAuthors',
		'adaptedBy',
		'translator',
		'compiler',
		'editorialStaff',
		'chiefEditor',
		'editor',
		'publisherEditor',
		'consultant',
		'artist',
		'artistEditor',
		'technicalEditor',
		'reviewer',
		'corrector',
	];

	private static $personPrefixes = [
		'д-р',
		'проф.',
		'проф. д-р',
		'акад.',
		'инж.',
	];

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

	public static function normalizedFieldValue($field, $value) {
		$value = self::normalizeGenericValue($value);
		if (self::isPersonField($field)) {
			return self::normalizePerson($value);
		}
		switch ($field) {
			case 'publisher':
				return self::normalizePublisher($value);
			case 'illustrated':
				return self::normalizeIllustrated($value);
			case 'isbn':
				return self::normalizeIsbn($value);
			case 'isbnClean':
				return self::normalizeSearchableIsbn($value);
		}
		return $value;
	}

	private static function normalizePerson($name) {
		$nameNormalized = $name;
		$nameNormalized = preg_replace('/^('.self::gluePrefixesForRegExp(self::$personPrefixes).') /u', '', $nameNormalized);
		$nameNormalized = preg_replace('/ \(.+\)$/', '', $nameNormalized);
		return $nameNormalized;
	}

	private static function normalizePublisher($name) {
		$nameNormalized = $name;
		$nameNormalized = preg_replace('/^('.self::gluePrefixesForRegExp(self::$publisherPrefixes).') ["„«]?/u', '', $nameNormalized);
		$nameNormalized = str_replace(self::$publisherStringsToRemove, '', $nameNormalized);
		$nameNormalized = trim($nameNormalized, ' ,-');
		if (empty($nameNormalized)) {
			// we do not want to be perfect
			return $name;
		}
		return $nameNormalized;
	}

	private static function normalizeGenericValue($value) {
		return trim(preg_replace('/ \(не е указан[ао]?|не е посочен[ао]?\)/u', '', $value));
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

	private function isPersonField($field) {
		return in_array($field, self::$personFields);
	}
}
