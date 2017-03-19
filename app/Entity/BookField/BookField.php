<?php namespace App\Entity\BookField;

class BookField {

	private $value;

	public function __construct($value) {
		$this->value = static::normalizeInput($value);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return static
	 */
	public static function fromName($name, $value) {
		$class = Map::classForField($name);
		if ($class) {
			return new $class($value);
		}
		return new static($value);
	}

	public static function normalizeInput($input) {
		return $input;
	}

	public static function normalizedFieldValue($fieldName, $value) {
		$value = self::normalizeGenericValue($value);
		return self::fromName($fieldName, $value)->value;
	}

	private static function normalizeGenericValue($value) {
		return trim(preg_replace('/ \(не е указан[ао]?|не е посочен[ао]?\)/u', '', $value));
	}
}
