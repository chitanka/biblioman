<?php namespace App\Entity\BookField;

class Map {

	private static $fieldClasses = [
		'author' => Person::class,
		'translator' => Person::class,
		'adaptedBy' => Person::class,
		'otherAuthors' => Person::class,
		'compiler' => Person::class,
		'chiefEditor' => Person::class,
		'managingEditor' => Person::class,
		'editor' => Person::class,
		'editorialStaff' => Person::class,
		'publisherEditor' => Person::class,
		'artistEditor' => Person::class,
		'technicalEditor' => Person::class,
		'consultant' => Person::class,
		'scienceEditor' => Person::class,
		'copyreader' => Person::class,
		'reviewer' => Person::class,
		'artist' => Person::class,
		'illustrator' => Person::class,
		'corrector' => Person::class,
		'layout' => Person::class,
		'coverLayout' => Person::class,
		'libraryDesign' => Person::class,
		'computerProcessing' => Person::class,
		'prepress' => Person::class,
		'illustrated' => Illustrated::class,
		'isbn' => Isbn::class,
		'isbnClean' => IsbnClean::class,
		'publisher' => Publisher::class,
	];

	public static function classForField($field) {
		if (isset(self::$fieldClasses[$field])) {
			return self::$fieldClasses[$field];
		}
		return null;
	}

	public static function isFieldForPerson($field) {
		if (!isset(self::$fieldClasses[$field])) {
			return false;
		}
		return self::$fieldClasses[$field] === Person::class;
	}
}
